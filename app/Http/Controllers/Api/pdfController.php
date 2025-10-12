<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Turma;

class pdfController extends Controller
{
    public function gerarPdf()
    {

        $turmas = Turma::all();

        $pdf = Pdf::loadView('relatorios.pdf', compact('turmas'));

        return $pdf->stream('relatorio.pdf');
    }
}
