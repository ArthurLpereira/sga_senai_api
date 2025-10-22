<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse; // Importa a classe para o CSV

class csvController extends Controller
{


    public function gerarCsv()
    {
        // ETAPAS 1, 2 e 3 são idênticas para buscar e agrupar os dados
        $agora = Carbon::now();
        $mesAtual = $agora->month;
        if ($mesAtual <= 6) {
            $inicioSemestre = $agora->copy()->startOfYear();
            $fimSemestre = $agora->copy()->month(6)->endOfMonth();
        } else {
            $inicioSemestre = $agora->copy()->month(7)->startOfMonth();
            $fimSemestre = $agora->copy()->endOfYear();
        }
        $turmasDoSemestre = Turma::with(['ambiente', 'curso', 'minutosAula', 'statusTurma', 'turno'])
            ->where('data_inicio_turma', '<=', $fimSemestre)
            ->where('data_termino_turma', '>=', $inicioSemestre)
            ->get();
        $turmasPorMes = [];
        $periodoDoSemestre = CarbonPeriod::create($inicioSemestre, '1 month', $fimSemestre);
        foreach ($periodoDoSemestre as $dataMes) {
            $nomeMes = ucfirst($dataMes->locale('pt_BR')->monthName);
            $inicioDoMesAtual = $dataMes->copy()->startOfMonth();
            $fimDoMesAtual = $dataMes->copy()->endOfMonth();
            $turmasAtivasNoMes = $turmasDoSemestre->filter(function ($turma) use ($inicioDoMesAtual, $fimDoMesAtual) {
                return $turma->data_inicio_turma <= $fimDoMesAtual && $turma->data_termino_turma >= $inicioDoMesAtual;
            });
            $turmasPorMes[$nomeMes] = $turmasAtivasNoMes;
        }

        // =========================================================================
        // ETAPA 4: GERAR E ENVIAR A RESPOSTA CSV ESTRUTURADA EM COLUNAS
        // =========================================================================
        $fileName = 'relatorio_semestral.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($turmasPorMes) {
            $handle = fopen('php://output', 'w');

            // BOM (Byte Order Mark) para UTF-8, ajuda o Excel a abrir acentos corretamente
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Define os TÍTULOS de cada coluna na primeira linha do ficheiro
            fputcsv($handle, [
                'Mês de Atividade',
                'ID da Turma',
                'Nome da Turma',
                'Nome do Curso',
                'Turno',
                'Ambiente/Sala',
                'Status da Turma',
                'Data de Início',
                'Data de Término',
            ], ';'); // <-- A MUDANÇA É AQUI

            // Itera sobre os dados agrupados
            foreach ($turmasPorMes as $nomeMes => $turmas) {
                foreach ($turmas as $turma) {
                    // Monta a linha de DADOS, garantindo a mesma ordem dos títulos
                    fputcsv($handle, [
                        $nomeMes,
                        $turma->id,
                        $turma->nome_turma ?? 'N/A', // Ajuste este campo se o nome for diferente
                        $turma->curso?->nome_curso ?? 'N/A',
                        $turma->turno?->nome_turno ?? 'N/A',
                        $turma->ambiente?->nome_ambiente ?? 'N/A',
                        $turma->statusTurma?->nome_status_turma ?? 'N/A',
                        sprintf('="%s"', Carbon::parse($turma->data_inicio_turma)->format('d/m/Y')),
                        sprintf('="%s"', Carbon::parse($turma->data_termino_turma)->format('d/m/Y')),
                    ], ';');
                }
            }

            fclose($handle);
        };

        // Retorna a resposta para o navegador iniciar o download
        return new StreamedResponse($callback, 200, $headers);
    }
}
