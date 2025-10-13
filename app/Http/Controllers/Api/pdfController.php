<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Turma;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class pdfController extends Controller
{
    public function gerarPdf()
    {
        // =========================================================================
        // ETAPA 1: DETERMINAR O SEMESTRE ATUAL E SUAS DATAS DE INÍCIO/FIM
        // =========================================================================
        $agora = Carbon::now();
        $mesAtual = $agora->month;

        if ($mesAtual <= 6) { // Primeiro semestre (Janeiro a Junho)
            $inicioSemestre = $agora->copy()->startOfYear();
            $fimSemestre = $agora->copy()->month(6)->endOfMonth();
        } else { // Segundo semestre (Julho a Dezembro)
            $inicioSemestre = $agora->copy()->month(7)->startOfMonth();
            $fimSemestre = $agora->copy()->endOfYear();
        }

        // =========================================================================
        // ETAPA 2: BUSCAR TODAS AS TURMAS QUE CRUZAM COM O PERÍODO DO SEMESTRE
        // Isso nos dá a nossa lista inicial de "turmas candidatas".
        // =========================================================================
        $turmasDoSemestre = Turma::with(['ambiente', 'curso', 'minutosAula', 'statusTurma', 'turno'])
            // A data de início da turma deve ser ANTES do fim do semestre
            ->where('data_inicio_turma', '<=', $fimSemestre)
            // E a data de término da turma deve ser DEPOIS do início do semestre
            ->where('data_termino_turma', '>=', $inicioSemestre)
            ->get();

        // =========================================================================
        // ETAPA 3: AGRUPAR AS TURMAS POR MÊS
        // Aqui nós processamos a lista em PHP para montar a estrutura final.
        // =========================================================================
        $turmasPorMes = [];
        // Cria um período que itera mês a mês dentro do semestre
        $periodoDoSemestre = CarbonPeriod::create($inicioSemestre, '1 month', $fimSemestre);

        foreach ($periodoDoSemestre as $dataMes) {
            // Pega o nome do mês em português (ex: "Janeiro", "Fevereiro")
            $nomeMes = ucfirst($dataMes->locale('pt_BR')->monthName);
            $inicioDoMesAtual = $dataMes->copy()->startOfMonth();
            $fimDoMesAtual = $dataMes->copy()->endOfMonth();

            // Filtra a coleção de turmas do semestre para pegar apenas as ativas neste mês
            $turmasAtivasNoMes = $turmasDoSemestre->filter(function ($turma) use ($inicioDoMesAtual, $fimDoMesAtual) {
                // A lógica é a mesma: a turma precisa cruzar com o período do mês atual
                return $turma->data_inicio_turma <= $fimDoMesAtual && $turma->data_termino_turma >= $inicioDoMesAtual;
            });

            // Adiciona a lista de turmas (mesmo que vazia) ao nosso array final
            $turmasPorMes[$nomeMes] = $turmasAtivasNoMes;
        }

        // Passamos o array já agrupado para a view
        $pdf = Pdf::loadView('relatorios.pdf', compact('turmasPorMes'));

        return $pdf->stream('relatorio_semestral.pdf');
    }
}
