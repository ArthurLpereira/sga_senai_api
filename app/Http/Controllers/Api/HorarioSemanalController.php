<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ambiente;
use Carbon\Carbon;

class HorarioSemanalController extends Controller
{
    /**
     * Busca e formata o horário semanal com base numa data.
     */
    public function getHorarioSemanal(Request $request)
    {
        $dataBase = Carbon::parse($request->input('data', 'today'));
        $inicioSemana = $dataBase->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $fimSemana = $dataBase->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');

        $ambientes = Ambiente::with([
            'turmas' => function ($query) use ($inicioSemana, $fimSemana) {
                $query->where('data_inicio_turma', '<=', $fimSemana)
                    ->where('data_termino_turma', '>=', $inicioSemana);
            },
            'turmas.diasDaSemana',
            'turmas.curso',
            'turmas.turno'
        ])->orderBy('nome_ambiente', 'asc')->get();

        $horarioFormatado = $ambientes->map(function ($ambiente) {
            $horarioAmbiente = [];
            for ($dia = 1; $dia <= 7; $dia++) {
                $horarioAmbiente[$dia] = ['M' => [], 'T' => [], 'N' => []];
            }

            foreach ($ambiente->turmas as $turma) {
                $turnoLetra = $turma->turno ? strtoupper(substr($turma->turno->nome_turno, 0, 1)) : null;
                if (!$turnoLetra) continue;

                $dadosDaTurma = [
                    'turma_nome' => $turma->nome_turma,
                    'curso_nome' => $turma->curso->nome_curso,
                    'cor_curso' => $turma->curso->cor_curso,
                ];

                // --- A CORREÇÃO ESTÁ AQUI ---
                // O seu Model `DiasDasSemana` tem a chave primária `id`.
                // Devemos aceder a `$dia->id`, e não a `$dia->id_dia_da_semana` (que não existe).
                foreach ($turma->diasDaSemana as $dia) {
                    $horarioAmbiente[$dia->id][$turnoLetra][] = $dadosDaTurma;
                }
            }

            return [
                'id_ambiente' => $ambiente->id,
                'nome_ambiente' => $ambiente->nome_ambiente,
                'horario' => $horarioAmbiente,
            ];
        });

        return response()->json([
            'data_inicio' => $inicioSemana,
            'data_fim' => $fimSemana,
            'horarios' => $horarioFormatado,
        ]);
    }
}
