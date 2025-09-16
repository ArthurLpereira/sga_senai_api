<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ambiente;
use Carbon\Carbon;

class HorarioSemanal extends Controller
{
    /**
     * Busca e formata o horário semanal com base numa data,
     * permitindo múltiplas turmas por horário e ignorando turmas sem dias de aula definidos.
     */
    public function getHorarioSemanal(Request $request)
    {
        // 1. Valida a data recebida do front-end (via parâmetro de URL ?data=...).
        //    Se nenhuma data for fornecida, usa a data de hoje como base.
        $dataBase = Carbon::parse($request->input('data', 'today'));
        
        // Calcula o primeiro dia da semana (Domingo) e o último (Sábado).
        $inicioSemana = $dataBase->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $fimSemana = $dataBase->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');

        // 2. Busca todos os ambientes. Para cada ambiente, carrega as suas turmas
        //    que estão ativas durante a semana que estamos a visualizar.
        $ambientes = Ambiente::with([
            'turmas' => function ($query) use ($inicioSemana, $fimSemana) {
                // ESTE É O FILTRO PRINCIPAL
                // Ele seleciona apenas as turmas cujo período de duração
                // se sobrepõe ao período da semana que estamos a visualizar.
                $query->where('data_inicio_turma', '<=', $fimSemana)
                      ->where('data_termino_turma', '>=', $inicioSemana);
            },
            // Carrega os relacionamentos das turmas para evitar o problema N+1 e ter os dados para a resposta.
            'turmas.curso',
            'turmas.diasDaSemana',
            'turmas.turno'
        ])->get();

        // 3. Estrutura os dados para serem facilmente consumidos pelo front-end.
        $horarioFormatado = $ambientes->map(function ($ambiente) {
            
            // Inicializa uma grelha de horário vazia para este ambiente.
            // Cada célula da grelha é um array vazio (`[]`), pronto para receber uma ou mais turmas.
            $horarioAmbiente = [];
            for ($dia = 1; $dia <= 7; $dia++) { // Usamos de 1 a 7 para corresponder aos seus IDs de dias da semana
                $horarioAmbiente[$dia] = [
                    'M' => [], // Manhã agora é um array
                    'T' => [], // Tarde agora é um array
                    'N' => [], // Noite agora é um array
                ];
            }

            // Preenche a grelha com as turmas que foram encontradas
            foreach ($ambiente->turmas as $turma) {
                // A lógica para encontrar a letra do turno (M, T, ou N)
                $turnoLetra = $turma->turno ? substr($turma->turno->nome_turno, 0, 1) : null;
                // Se a turma não tiver um turno válido, pulamos para a próxima
                if (!$turnoLetra) {
                    continue; 
                }

                // Prepara os dados da turma que serão mostrados no horário
                $dadosDaTurma = [
                    'turma_nome' => $turma->nome_turma,
                    'curso_nome' => $turma->curso->nome_curso,
                    'cor_curso' => $turma->curso->cor_curso,
                ];

                // --- A CORREÇÃO ESTÁ AQUI ---
                // Para cada dia da semana em que esta turma tem aula...
                // Se a turma não tiver dias associados, este loop simplesmente não executa,
                // e a turma não é adicionada a nenhuma célula da grelha, prevenindo o erro da chave vazia.
                foreach ($turma->diasDaSemana as $dia) {
                    // Adiciona os dados da turma ao array correspondente ao dia e turno.
                    // A chave `$dia->id` (ou `id_dia_da_semana`) deve corresponder ao ID na sua tabela `dias_das_semanas`.
                    $horarioAmbiente[$dia->id_dia_da_semana][$turnoLetra][] = $dadosDaTurma;
                }
            }

            return [
                'id_ambiente' => $ambiente->id, // Assumindo que a PK de ambientes é 'id'
                'nome_ambiente' => $ambiente->nome_ambiente,
                'horario' => $horarioAmbiente,
            ];
        });

        // 4. Retorna a resposta JSON final
        return response()->json([
            'data_inicio' => $inicioSemana,
            'data_fim' => $fimSemana,
            'horarios' => $horarioFormatado,
        ]);
    }
}

