<?php

namespace App\Http\Controllers\Api;

use App\Models\Ambiente;
use Illuminate\Http\Request;
use App\Http\Requests\AmbienteRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AmbienteResource;
use Carbon\Carbon;
use App\Models\Turma;
use App\Models\TiposAmbiente;

class AmbienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Adicionado: .with('tipoAmbiente') para carregar a relação (Eager Loading)
        $ambientes = Ambiente::with('tipoAmbiente')->paginate();

        return AmbienteResource::collection($ambientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AmbienteRequest $request): AmbienteResource
    {
        $ambiente = Ambiente::create($request->validated());

        // Adicionado: .load('tipoAmbiente') para carregar a relação no modelo recém-criado
        $ambiente->load('tipoAmbiente');

        // Retornar o resource diretamente ajusta o status HTTP para 201 Created automaticamente
        return new AmbienteResource($ambiente);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ambiente $ambiente): AmbienteResource
    {
        // Removido: A linha '$ambiente = Ambiente::find($ambiente);' era redundante e incorreta
        // O Laravel já injeta o objeto $ambiente correto (Route-Model Binding)

        // Adicionado: .load('tipoAmbiente') para carregar a relação
        $ambiente->load('tipoAmbiente');

        return new AmbienteResource($ambiente);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AmbienteRequest $request, Ambiente $ambiente): AmbienteResource
    {
        $ambiente->update($request->validated());

        // Adicionado: .load('tipoAmbiente') para carregar a relação no modelo atualizado
        $ambiente->load('tipoAmbiente');

        return new AmbienteResource($ambiente);
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Ambiente $ambiente): Response
    {
        $ambiente->delete();

        return response()->noContent();
    }

    public function toggleStatus(Ambiente $ambiente): AmbienteResource
    {
        // 1. Lógica de Alternância (Toggle):
        $ambiente->status_ambiente = ($ambiente->status_ambiente == '1') ? '0' : '1';

        // 2. Guarda a alteração na base de dados.
        $ambiente->save();

        // 3. Resposta: Retorna o ambiente completo e atualizado, formatado pelo Resource.
        //    O `load` garante que o nome do tipo de ambiente é carregado para a resposta.
        return new AmbienteResource($ambiente->load('tipoAmbiente'));
    }

    public function getAmbientesDisponiveis(): JsonResponse
    {
        // Conta todos os ambientes no total
        $totalAmbientes = Ambiente::count();

        // Conta apenas os ambientes com status '1' (disponíveis)
        $ambientesDisponiveis = Ambiente::where('status_ambiente', '1')->count();

        // Calcula os indisponíveis
        $ambientesIndisponiveis = $totalAmbientes - $ambientesDisponiveis;

        // Retorna todos os dados de uma vez
        return response()->json([
            'total_ambientes'      => $totalAmbientes,
            'quantidade_disponiveis' => $ambientesDisponiveis,
            'quantidade_indisponiveis' => $ambientesIndisponiveis,
        ]);
    }

    public function getTaxaOcupacao(): JsonResponse
    {
        $totalAmbientes = Ambiente::count();
        $ambientesDisponiveis = Ambiente::where('status_ambiente', '0')->count();
        $ambientesOcupados = $totalAmbientes - $ambientesDisponiveis;

        // Evitar divisão por zero
        if ($totalAmbientes === 0) {
            return response()->json([
                'taxa_ocupacao' => 0,
                'mensagem' => 'Nenhum ambiente cadastrado.'
            ]);
        }

        $taxaOcupacao = ($ambientesOcupados / $totalAmbientes) * 100;

        return response()->json([
            'taxa_ocupacao' => round($taxaOcupacao, 2) // Arredondar para 2 casas decimais
        ]);
    }

    // Adicione esta função ao seu controller, por exemplo, app/Http/Controllers/AmbienteController.php
    // Substitua a função no seu AmbienteController.php por esta versão

    public function getTaxaOcupacaoPorTipoAmbiente(): JsonResponse
    {
        // A busca inicial não muda
        $todosAmbientes = Ambiente::with('tipoAmbiente')->get();

        // As contagens iniciais não mudam
        $quantidadeInativos = $todosAmbientes->where('status_ambiente', '0')->count();
        $ambientesAtivos = $todosAmbientes->where('status_ambiente', '1');
        $totalAmbientesAtivos = $ambientesAtivos->count();

        // O agrupamento por tipo também não muda
        $detalhesPorTipo = $ambientesAtivos
            ->groupBy(function ($ambiente) {
                return $ambiente->tipoAmbiente->nome_tipo_ambiente ?? 'Tipo não definido';
            })
            ->map(function ($grupo) use ($totalAmbientesAtivos) {
                $quantidadeNoGrupo = $grupo->count();
                $percentual = ($totalAmbientesAtivos > 0)
                    ? ($quantidadeNoGrupo / $totalAmbientesAtivos) * 100
                    : 0;

                return [
                    'quantidade_neste_tipo' => $quantidadeNoGrupo,
                    'percentual_do_total_ativo' => round($percentual, 2),
                ];
            });

        // --- NOVA LÓGICA ADICIONADA AQUI ---
        $totalAmbientesCadastrados = $todosAmbientes->count();

        // Novo cálculo: Porcentagem de inativos sobre o total geral
        $percentualInativos = ($totalAmbientesCadastrados > 0)
            ? ($quantidadeInativos / $totalAmbientesCadastrados) * 100
            : 0;
        // --- FIM DA NOVA LÓGICA ---

        // Montamos o array final da resposta, agora com a nova informação
        $resultado = [
            'visao_geral' => [
                'total_ambientes_cadastrados' => $totalAmbientesCadastrados,
                'total_ambientes_ativos' => $totalAmbientesAtivos,
                'total_ambientes_inativos' => $quantidadeInativos,
            ],
            'ocupacao_por_tipo' => $detalhesPorTipo,
        ];

        return response()->json($resultado);
    }

    public function getOcupacaoSemanal(): JsonResponse
    {
        // 1. Total de ambientes para o cálculo (Denominador)
        // Estou pegando TODOS para garantir que não dê erro de divisão por zero.
        // Se quiser apenas os ativos, use: ->where('status_ambiente', '1')->count();
        $totalAmbientes = Ambiente::count();

        if ($totalAmbientes == 0) {
            return response()->json([]);
        }

        // 2. Mapeamento: ID no Banco (tabela dias_da_semana) => Sigla no Gráfico
        $diasSemana = [
            2 => 'Seg',
            3 => 'Ter',
            4 => 'Qua',
            5 => 'Qui',
            6 => 'Sex',
            7 => 'Sáb'
        ];

        $dadosGrafico = [];

        foreach ($diasSemana as $idDia => $siglaTela) {

            // 3. A Query Principal
            $ambientesOcupadosNesteDia = Turma::query()
                ->where('status_turma_id', 3) // <--- AQUI ESTÁ A REGRA: Apenas turmas ativas (Iniciadas)
                ->whereHas('diasDaSemana', function ($query) use ($idDia) {
                    // Filtra pela tabela pivot onde o dia é o do loop atual
                    // Garante que a tabela pivot se chama 'dia_da_semana_turma'
                    $query->where('dia_da_semana_turma.dia_da_semana_id', $idDia);
                })
                ->whereNotNull('ambiente_id') // Turma tem que ter sala definida
                ->distinct('ambiente_id')     // Se tiver 2 turmas na mesma sala no mesmo dia, conta só 1 ocupação
                ->count('ambiente_id');

            // 4. Cálculo da porcentagem
            $percentual = ($ambientesOcupadosNesteDia / $totalAmbientes) * 100;

            $dadosGrafico[] = [
                'dia' => $siglaTela,
                'percentual' => round($percentual, 0),
                // Debug: se quiser ver no inspecionar elemento quantos achou
                'debug_qtd' => $ambientesOcupadosNesteDia
            ];
        }

        return response()->json($dadosGrafico);
    }
}
