<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ambiente;
use App\Models\DiasNaoLetivo; // Certifique-se de que este Model existe
use Illuminate\Http\Request;
use Carbon\Carbon;

class HorarioMensalController extends Controller
{
    /**
     * Busca e formata o horário de um mês inteiro no formato de grelha por ambiente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHorarioMensal(Request $request)
    {
        // 1. Validação e cálculo das datas do mês
        $request->validate(['data' => 'nullable|date_format:Y-m-d']);
        $dataBase = Carbon::parse($request->input('data', 'today'));

        $inicioMes = $dataBase->copy()->startOfMonth();
        $fimMes = $dataBase->copy()->endOfMonth();

        // 2. Busca todos os dias não letivos do mês de uma só vez para otimização
        $diasNaoLetivos = DiasNaoLetivo::whereBetween('data_dia_nao_letivo', [$inicioMes->format('Y-m-d'), $fimMes->format('Y-m-d')])
            ->pluck('data_dia_nao_letivo') // Extrai apenas a coluna 'data'
            ->map(fn($data) => Carbon::parse($data)->format('Y-m-d')) // Garante o formato
            ->toArray();

        // 3. Busca todos os ambientes e suas turmas ativas no mês (semelhante ao semanal)
        $ambientes = Ambiente::with([
            'turmas' => function ($query) use ($inicioMes, $fimMes) {
                $query->where('data_inicio_turma', '<=', $fimMes->format('Y-m-d'))
                    ->where('data_termino_turma', '>=', $inicioMes->format('Y-m-d'));
            },
            'turmas.curso',
            'turmas.turno',
            'turmas.diasDaSemana'
        ])->orderBy('nome_ambiente', 'asc')->get();

        // 4. Mapeia os ambientes para construir a estrutura final do calendário
        $calendarioFormatado = $ambientes->map(function ($ambiente) use ($inicioMes, $fimMes, $diasNaoLetivos) {

            // --- PASSO A: Pré-processamento das turmas ---
            // Organiza as turmas por [dia_da_semana][turno] para acesso rápido, evitando loops desnecessários
            $turmasPorDiaDaSemana = [];
            foreach ($ambiente->turmas as $turma) {
                $turnoLetra = $turma->turno ? strtoupper(substr($turma->turno->nome_turno, 0, 1)) : null;
                if (!$turnoLetra) continue;

                $dadosDaTurma = [
                    'turma_nome' => $turma->nome_turma,
                    'curso_nome' => $turma->curso->nome_curso,
                    'cor_curso' => $turma->curso->cor_curso,
                ];

                foreach ($turma->diasDaSemana as $dia) {
                    // Inicializa os arrays se ainda não existirem
                    if (!isset($turmasPorDiaDaSemana[$dia->id])) {
                        $turmasPorDiaDaSemana[$dia->id] = ['M' => [], 'T' => [], 'N' => []];
                    }
                    $turmasPorDiaDaSemana[$dia->id][$turnoLetra][] = $dadosDaTurma;
                }
            }

            // --- PASSO B: Construção da Grelha do Mês ---
            $calendarioDoAmbiente = [];
            // Itera por cada dia do mês, do primeiro ao último
            for ($diaAtual = $inicioMes->copy(); $diaAtual->lte($fimMes); $diaAtual->addDay()) {
                $diaFormatado = $diaAtual->format('Y-m-d');

                // Verifica se o dia atual está na lista de dias não letivos
                if (in_array($diaFormatado, $diasNaoLetivos)) {
                    $calendarioDoAmbiente[$diaFormatado] = [
                        'status' => 'Dia Não Letivo'
                    ];
                } else {
                    // Pega o ID do dia da semana (1=Domingo, 2=Segunda, etc.)
                    $diaDaSemanaId = $diaAtual->dayOfWeek + 1;

                    // Pega as turmas pré-processadas para este dia da semana ou um array vazio
                    $calendarioDoAmbiente[$diaFormatado] = $turmasPorDiaDaSemana[$diaDaSemanaId]
                        ?? ['M' => [], 'T' => [], 'N' => []];
                }
            }

            return [
                'id_ambiente' => $ambiente->id,
                'nome_ambiente' => $ambiente->nome_ambiente,
                'calendario' => $calendarioDoAmbiente, // A chave agora é 'calendario'
            ];
        });

        // 5. Retorna a resposta final em JSON
        return response()->json([
            'mes_referencia' => $dataBase->format('Y-m'),
            'horarios' => $calendarioFormatado,
        ]);
    }
}
