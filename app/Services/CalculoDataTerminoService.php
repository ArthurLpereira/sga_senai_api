<?php

namespace App\Services;

use App\Models\DiasNaoLetivo;
use Carbon\Carbon;

class CalculoDataTerminoService
{
    /**
     * Calcula a data de término de um curso com base nos dados fornecidos.
     */
    public function calcular(string $dataInicio, int $cargaHorariaTotalEmHoras, int $duracaoAulaEmMinutos, array $diasDaSemanaIds): string
    {
        $diasNaoLetivos = DiasNaoLetivo::pluck('data_dia_nao_letivo')->toArray();
        $cargaHorariaDiaria = $duracaoAulaEmMinutos / 60;

        if ($cargaHorariaDiaria <= 0) {
            return $dataInicio;
        }

        $dataAtual = Carbon::parse($dataInicio);
        $cargaHorariaRestante = $cargaHorariaTotalEmHoras;

        // --- A CORREÇÃO ESTÁ AQUI ---
        // Agora, o mapeamento corresponde à sua base de dados (1=Seg, ..., 7=Dom)
        // e converte para o padrão do Carbon (1=Seg, ..., 0=Dom).
        $diasDeAulaCarbon = array_map(function ($id) {
            // Se o ID for 7 (Domingo), mapeia para 0.
            // Caso contrário, o ID já corresponde ao dia da semana do Carbon.
            return ($id == 7) ? 0 : $id;
        }, $diasDaSemanaIds);

        while ($cargaHorariaRestante > 0.001) {
            $eDiaDeAula = in_array($dataAtual->dayOfWeek, $diasDeAulaCarbon);
            $eDiaNaoLetivo = in_array($dataAtual->format('Y-m-d'), $diasNaoLetivos);

            if ($eDiaDeAula && !$eDiaNaoLetivo) {
                $cargaHorariaRestante -= $cargaHorariaDiaria;
            }

            if ($cargaHorariaRestante > 0.001) {
                $dataAtual->addDay();
            }
        }

        return $dataAtual->format('Y-m-d');
    }
}
