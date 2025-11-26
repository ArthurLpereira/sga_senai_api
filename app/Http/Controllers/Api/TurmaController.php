<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TurmaRequest;
use App\Http\Resources\TurmaResource;
use App\Models\Turma;
use App\Models\Ambiente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\CalculoDataTerminoService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\DiasNaoLetivo;

class TurmaController extends Controller
{
    /**
     * 
     * 
     * Mostra uma lista de todas as turmas.
     */


    public function index()
    {
        // Carrega todos os relacionamentos necessários para a lista
        $turmas = Turma::with(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana'])->paginate(15);
        return TurmaResource::collection($turmas);
    }

    /**
     * Guarda uma nova turma E liga os seus relacionamentos na tabela pivot.
     */
    private function ajustarDataTerminoComFeriados($dataInicio, $dataTerminoOriginal, $diasSemanaTurma)
    {
        $inicio = Carbon::parse($dataInicio);
        $fim = Carbon::parse($dataTerminoOriginal);

        // 1. Busca todos os feriados
        $feriados = DiasNaoLetivo::pluck('data_dia_nao_letivo')->toArray();

        $aulasPerdidas = 0;
        $periodoOriginal = CarbonPeriod::create($inicio, $fim);

        // 2. Conta quantas aulas caíram em feriados no período original
        foreach ($periodoOriginal as $data) {
            $diaSemana = $data->dayOfWeek + 1; // 1=Dom, 2=Seg...
            $dataStr = $data->format('Y-m-d');

            if (in_array($diaSemana, $diasSemanaTurma) && in_array($dataStr, $feriados)) {
                $aulasPerdidas++;
            }
        }

        // Se não houve conflito, retorna a data original
        if ($aulasPerdidas === 0) {
            return $fim->format('Y-m-d');
        }

        // 3. Adiciona os dias perdidos no final
        $novaDataFim = $fim->copy();

        while ($aulasPerdidas > 0) {
            $novaDataFim->addDay();

            $diaSemana = $novaDataFim->dayOfWeek + 1;
            $dataStr = $novaDataFim->format('Y-m-d');

            // Verifica se é dia de aula E não é outro feriado
            if (in_array($diaSemana, $diasSemanaTurma)) {
                if (!in_array($dataStr, $feriados)) {
                    $aulasPerdidas--; // Aula reposta com sucesso
                }
            }
        }

        return $novaDataFim->format('Y-m-d');
    }

    /**
     * Criação da Turma
     */
    public function store(TurmaRequest $request): JsonResponse
    {
        $turma = DB::transaction(function () use ($request) {

            // 1. Pega os dados validados do Request
            $dadosParaSalvar = $request->validated();

            // =================================================================
            // [ALTERAÇÃO] CÁLCULO DA DATA REAL ANTES DE SALVAR
            // =================================================================
            // Verificamos se temos os dias da semana para poder fazer o cálculo
            if ($request->has('dias_da_semana_ids') && !empty($request->dias_da_semana_ids)) {

                $novaDataTermino = $this->ajustarDataTerminoComFeriados(
                    $dadosParaSalvar['data_inicio_turma'],
                    $dadosParaSalvar['data_termino_turma'],
                    $request->dias_da_semana_ids // Array com IDs (ex: [2, 4])
                );

                // Substitui a data original pela data calculada (estendida)
                $dadosParaSalvar['data_termino_turma'] = $novaDataTermino;
            }
            // =================================================================

            // 2. Cria a turma usando os dados JÁ ALTERADOS (com a data nova)
            $novaTurma = Turma::create($dadosParaSalvar);

            // 3. Attach Colaboradores
            if ($request->has('colaboradores_ids')) {
                $novaTurma->colaboradores()->attach($request->colaboradores_ids);
            }

            // 4. Attach Dias da Semana
            if ($request->has('dias_da_semana_ids')) {
                $novaTurma->diasDaSemana()->attach($request->dias_da_semana_ids);
            }

            return $novaTurma;
        });

        // Carrega relacionamentos para o retorno
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana']);

        return response()->json(new TurmaResource($turma), 201);
    }

    /**
     * Mostra uma turma específica.
     */
    public function show(Turma $turma): JsonResponse
    {
        // Carrega todos os relacionamentos no objeto encontrado
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana']);
        return response()->json(new TurmaResource($turma));
    }

    /**
     * Atualiza uma turma existente E os seus relacionamentos na tabela pivot.
     */
    public function update(TurmaRequest $request, Turma $turma): JsonResponse
    {
        $turma = DB::transaction(function () use ($request, $turma) {
            // Atualiza os dados da tabela principal 'turmas'
            $turma->update($request->validated());

            // Para atualizações, usamos sync() em vez de attach().
            // O sync() apaga as ligações antigas e insere apenas as que vieram na requisição.
            // É a forma correta de atualizar um relacionamento Muitos-para-Muitos.
            if ($request->has('colaboradores_ids')) {
                $turma->colaboradores()->sync($request->colaboradores_ids);
            }

            if ($request->has('dias_da_semana_ids')) {
                $turma->diasDaSemana()->sync($request->dias_da_semana_ids);
            }

            return $turma;
        });

        // Carrega os relacionamentos para a resposta ser completa
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana']);

        return response()->json(new TurmaResource($turma));
    }

    /**
     * Apaga uma turma.
     */
    public function destroy(Turma $turma): Response
    {
        $turma->delete();
        return response()->noContent();
    }

    public function calcularDataTermino(Request $request, CalculoDataTerminoService $calculoService): JsonResponse
    {
        // 2. Valida os dados de entrada que vêm do formulário (Postman).
        $validator = Validator::make($request->all(), [
            'data_inicio_turma' => 'required|date_format:Y-m-d',
            'carga_horaria_total' => 'required|integer|min:1', // Carga total em horas
            'duracao_aula_minutos' => 'required|integer|min:1', // Duração da aula em minutos
            'dias_da_semana_ids' => 'required|array|min:1',
            'dias_da_semana_ids.*' => 'exists:dias_das_semanas,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dados = $validator->validated();

        // 3. Chama o nosso serviço para fazer o trabalho pesado.
        $dataTermino = $calculoService->calcular(
            $dados['data_inicio_turma'],
            $dados['carga_horaria_total'],
            $dados['duracao_aula_minutos'],
            $dados['dias_da_semana_ids']
        );

        // 4. Retorna a data calculada como uma resposta JSON.
        return response()->json([
            'data_termino_calculada' => $dataTermino,
        ]);
    }

    public function updateNome(Request $request, Turma $turma): JsonResponse
    {
        // Validação simples para o nome
        $request->validate([
            'nome_turma' => 'required|string|max:255',
        ]);

        // Atualiza apenas o campo 'nome'
        $turma->nome_turma = $request->input('nome_turma');
        $turma->save();

        // Carrega os relacionamentos para a resposta ser completa
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana']);

        return response()->json(new TurmaResource($turma));
    }

    public function getTurmasAtivas(): JsonResponse
    {
        $turmasAtivas = Turma::with(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana'])
            ->whereHas('statusTurma', function ($query) {
                $query->where('status_turma_id', '1');
            })
            ->get();

        $quantTurmas = $turmasAtivas->count();
        return response()->json([
            'quantidade' => $quantTurmas,
            'turmas' => TurmaResource::collection($turmasAtivas)
        ]);
    }

    public function updateAmbiente(Request $request, Turma $turma): JsonResponse
    {
        // Validação simples para o ambiente
        $request->validate([
            'ambiente_id' => 'required|exists:ambientes,id',
        ]);

        // Atualiza apenas o campo 'ambiente_id'
        $turma->ambiente_id = $request->input('ambiente_id');
        $turma->save();

        // Carrega os relacionamentos para a resposta ser completa
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana']);

        return response()->json(new TurmaResource($turma));
    }

    // app/Http/Controllers/TurmaController.php


    public function getTurmasSemanal(Request $request)
    {
        try {
            // =========================================================================
            // PASSO 1 e 2: Datas (Lógica Semanal)
            // =========================================================================
            $dataParam = $request->query('data');
            $dataReferencia = $dataParam ? Carbon::parse($dataParam) : Carbon::now();

            // Define domingo como início e sábado como fim
            $dataInicioSemana = $dataReferencia->copy()->startOfWeek(Carbon::SUNDAY);
            $dataFimSemana = $dataReferencia->copy()->endOfWeek(Carbon::SATURDAY);

            // =========================================================================
            // PASSO 3: Ambientes
            // =========================================================================
            $ambientes = Ambiente::orderBy('num_ambiente', 'asc')->get();

            // =========================================================================
            // PASSO 3.5: Buscar Dias Não Letivos (Feriados da Semana)
            // =========================================================================
            $diasNaoLetivos = \App\Models\DiasNaoLetivo::whereBetween('data_dia_nao_letivo', [
                $dataInicioSemana->format('Y-m-d'),
                $dataFimSemana->format('Y-m-d')
            ])->get();

            // =========================================================================
            // PASSO 4: Buscar Turmas
            // =========================================================================
            $turmasCandidatas = Turma::with(['diasDaSemana', 'turno'])
                ->where('data_inicio_turma', '<=', $dataFimSemana)
                ->where('data_termino_turma', '>=', $dataInicioSemana)
                ->get();

            $calendario = [];
            $periodoDaSemana = CarbonPeriod::create($dataInicioSemana, $dataFimSemana);

            // Otimização: Mapeia dias de aula
            $diasDeAulaPorTurma = [];
            foreach ($turmasCandidatas as $turma) {
                $diasDeAulaPorTurma[$turma->id] = $turma->diasDaSemana->pluck('id')->all();
            }

            // =========================================================================
            // LOOP PELOS DIAS DA SEMANA
            // =========================================================================
            foreach ($periodoDaSemana as $data) {
                $dataString = $data->toDateString();
                $agendamentosDoDia = []; // IMPORTANTE: Começa sempre como um array vazio

                // ---------------------------------------------------------------------
                // 1. Verifica se é dia não letivo PRIMEIRO
                // ---------------------------------------------------------------------
                $diaNaoLetivo = $diasNaoLetivos->firstWhere('data_dia_nao_letivo', $dataString);

                if ($diaNaoLetivo) {
                    // [PADRÃO IGUAL AO MENSAL]
                    // Adicionamos dentro do array [] para o front-end receber uma lista
                    $agendamentosDoDia[] = [
                        'id' => 'feriado_' . $diaNaoLetivo->id,
                        'tipo_evento' => 'nao_letivo', // Identificador para o Front pintar de cinza/vermelho
                        'titulo' => 'Dia não letivo',
                        'descricao' => $diaNaoLetivo->descricao_dia_nao_letivo,
                        'ambiente_id' => null // Importante: evita erro no filtro do front
                    ];

                    $calendario[$dataString] = $agendamentosDoDia;
                    continue; // Pula a busca de turmas para este dia
                }

                // ---------------------------------------------------------------------
                // 2. Se for dia normal, busca as turmas
                // ---------------------------------------------------------------------
                $diaDaSemanaAtual = $data->dayOfWeek + 1; // 1=Dom, 2=Seg...

                foreach ($turmasCandidatas as $turma) {
                    $temAulaNoDiaDaSemana = in_array($diaDaSemanaAtual, $diasDeAulaPorTurma[$turma->id] ?? []);
                    $estaNoPeriodoDaTurma = $data->between(
                        $turma->data_inicio_turma,
                        $turma->data_termino_turma
                    );

                    if ($temAulaNoDiaDaSemana && $estaNoPeriodoDaTurma) {
                        // Opcional: define tipo 'aula' para consistência
                        $turma->tipo_evento = 'aula';
                        $agendamentosDoDia[] = $turma;
                    }
                }

                $calendario[$dataString] = $agendamentosDoDia;
            }

            // =========================================================================
            // PASSO 5: Resposta
            // =========================================================================
            return response()->json([
                'ambientes' => $ambientes,
                'agendamentos' => $calendario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'erro' => 'Erro ao buscar calendário semanal.',
                'mensagem' => $e->getMessage()
            ], 500);
        }
    }
    // No seu TurmaController.php

    public function getTurmasDiario(Request $request)
    {
        try {
            $dataParam = $request->query('data');

            // Se ?data= não for enviado, usa hoje.
            // Carbon::parse() já entende 'YYYY-MM-DD' que o JavaScript enviará.
            $dataReferencia = $dataParam ? Carbon::parse($dataParam) : Carbon::now();

            // Calcula o dia da semana (1=Domingo, 2=Segunda, etc.)
            $diaDaSemana = $dataReferencia->dayOfWeek + 1;

            $turmasDoDia = Turma::with([
                'curso',         // <<< NECESSÁRIO PARA O MODAL
                'colaboradores', // <<< NECESSÁRIO PARA O MODAL (Docentes)
                'ambiente',      // <<< NECESSÁRIO PARA O MODAL
                'turno'          // (Já estava)
            ])
                // Filtro 1: A turma tem aula neste dia da semana?
                ->whereHas('diasDaSemana', function ($query) use ($diaDaSemana) {
                    $query->where('dias_das_semanas.id', $diaDaSemana);
                })
                // Filtro 2: A data de hoje está dentro do período de início e término da turma?
                ->where('data_inicio_turma', '<=', $dataReferencia->toDateString())
                ->where('data_termino_turma', '>=', $dataReferencia->toDateString())
                ->get();

            return response()->json($turmasDoDia);
        } catch (\Exception $e) {
            return response()->json([
                'erro' => 'Ocorreu um erro interno ou o parâmetro de data é inválido.',
                'mensagem' => $e->getMessage()
            ], 500);
        }
    }

    public function getTurmasMensal(Request $request)
    {
        try {
            // =========================================================================
            // CONFIGURAÇÃO DE DATAS
            // =========================================================================
            $ano = $request->query('ano');
            $mes = $request->query('mes');

            // Data padrão é hoje, a menos que passem ano/mês
            $dataReferencia = Carbon::now();
            if ($ano && $mes) {
                // Cria a data no dia 1 do mês solicitado
                $dataReferencia = Carbon::createFromDate($ano, $mes, 1);
            }

            $dataInicioMes = $dataReferencia->copy()->startOfMonth();
            $dataFimMes = $dataReferencia->copy()->endOfMonth();

            // =========================================================================
            // BUSCAS NO BANCO DE DADOS
            // =========================================================================

            // 1. Ambientes
            $ambientes = Ambiente::orderBy('num_ambiente', 'asc')->get();

            // 2. Dias Não Letivos (Feriados do mês inteiro)
            $diasNaoLetivos = \App\Models\DiasNaoLetivo::whereBetween('data_dia_nao_letivo', [
                $dataInicioMes->format('Y-m-d'),
                $dataFimMes->format('Y-m-d')
            ])->get();

            // 3. Turmas (Que acontecem neste mês)
            $turmasCandidatas = Turma::with(['diasDaSemana', 'turno'])
                ->where('data_inicio_turma', '<=', $dataFimMes)
                ->where('data_termino_turma', '>=', $dataInicioMes)
                ->get();

            // =========================================================================
            // MONTAGEM DO CALENDÁRIO
            // =========================================================================
            $calendario = [];
            $periodoDoMes = CarbonPeriod::create($dataInicioMes, $dataFimMes);

            // Otimização: Mapeia dias de aula das turmas em memória
            $diasDeAulaPorTurma = [];
            foreach ($turmasCandidatas as $turma) {
                $diasDeAulaPorTurma[$turma->id] = $turma->diasDaSemana->pluck('id')->all();
            }

            foreach ($periodoDoMes as $data) {
                $dataString = $data->toDateString(); // Y-m-d
                $agendamentosDoDia = []; // Começa vazio

                // --- VERIFICAÇÃO 1: FERIADO ---
                $diaNaoLetivo = $diasNaoLetivos->firstWhere('data_dia_nao_letivo', $dataString);

                if ($diaNaoLetivo) {
                    // [CORREÇÃO] Colocamos dentro de um array [] para manter o padrão
                    // Assim o front-end recebe uma lista, mesmo que seja de feriado.
                    $agendamentosDoDia[] = [
                        'id' => 'feriado_' . $diaNaoLetivo->id, // ID fictício para keys de front-end
                        'tipo_evento' => 'nao_letivo', // Identificador para pintar de outra cor
                        'titulo' => 'Dia não letivo',
                        'descricao' => $diaNaoLetivo->descricao_dia_nao_letivo,
                        'ambiente' => null // Não tem ambiente
                    ];

                    // Se for feriado, salvamos e passamos para o próximo dia (continue)
                    $calendario[$dataString] = $agendamentosDoDia;
                    continue;
                }

                // --- VERIFICAÇÃO 2: TURMAS (SÓ RODA SE NÃO FOR FERIADO) ---
                $diaDaSemanaAtual = $data->dayOfWeek + 1; // Carbon: 0=Dom -> Lógica: 1=Dom

                foreach ($turmasCandidatas as $turma) {
                    // Verifica dia da semana
                    $temAulaHoje = in_array($diaDaSemanaAtual, $diasDeAulaPorTurma[$turma->id] ?? []);

                    // Verifica intervalo de datas
                    $estaNoPeriodo = $data->between(
                        $turma->data_inicio_turma,
                        $turma->data_termino_turma
                    );

                    if ($temAulaHoje && $estaNoPeriodo) {
                        // Adiciona a turma à lista do dia
                        // Você pode adicionar um campo 'tipo_evento' => 'aula' se quiser diferenciar no front
                        $turma->tipo_evento = 'aula';
                        $agendamentosDoDia[] = $turma;
                    }
                }

                $calendario[$dataString] = $agendamentosDoDia;
            }

            return response()->json([
                'ambientes' => $ambientes,
                'agendamentos' => $calendario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'erro' => 'Erro interno ao buscar turmas.',
                'mensagem' => $e->getMessage(),
                'linha' => $e->getLine()
            ], 500);
        }
    }
}
