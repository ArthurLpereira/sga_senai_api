<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('ambientes/ambientes-disponiveis', [App\Http\Controllers\Api\AmbienteController::class, 'getAmbientesDisponiveis']);
Route::get('/ambientes/taxa-ocupacao', [App\Http\Controllers\Api\AmbienteController::class, 'getTaxaOcupacao']);
Route::get('turmas/turmas-ativas', [App\Http\Controllers\Api\TurmaController::class, 'getTurmasAtivas']);
Route::get('colaboradores/colaboradores-ativos', [App\Http\Controllers\Api\ColaboradoreController::class, 'getColaboradoresAtivos']);
Route::get('/ambientes/tipo-ambiente-taxa', [App\Http\Controllers\Api\AmbienteController::class, 'getTaxaOcupacaoPorTipoAmbiente']);
Route::get('/turmas/mensal', [App\Http\Controllers\Api\TurmaController::class, 'getTurmasMensal']);
Route::get('turmas/semanal', [App\Http\Controllers\Api\TurmaController::class, 'getTurmasSemanal']);
Route::get('turmas/diario', [App\Http\Controllers\Api\TurmaController::class, 'getTurmasDiario']);
Route::apiResource('ambientes', App\Http\Controllers\Api\AmbienteController::class);
Route::apiResource('tipos-colaboradores', App\Http\Controllers\Api\TiposColaboradoreController::class);
Route::apiResource('categorias-cursos', App\Http\Controllers\Api\CategoriasCursoController::class);
Route::apiResource('dias-nao-letivos', App\Http\Controllers\Api\DiasNaoLetivoController::class);
Route::apiResource('status-turmas', App\Http\Controllers\Api\StatusTurmaController::class);
Route::apiResource('cursos', App\Http\Controllers\Api\CursoController::class);
Route::apiResource('turnos', App\Http\Controllers\Api\TurnoController::class);
Route::apiResource('minutos-aulas', App\Http\Controllers\Api\MinutosAulaController::class);
Route::apiResource('dias-das-semanas', App\Http\Controllers\Api\DiasDasSemanaController::class);
Route::apiResource('colaboradores', App\Http\Controllers\Api\ColaboradoreController::class);
Route::apiResource('alteracoes-turmas', App\Http\Controllers\Api\AlteracoesTurmaController::class);
Route::apiResource('turmas', App\Http\Controllers\Api\TurmaController::class);
Route::apiResource('tipos-ambientes', App\Http\Controllers\Api\TiposAmbienteController::class);
Route::apiResource('colaboradores-has-turmas', App\Http\Controllers\Api\ColaboradoresHasTurmaController::class);
Route::post('/turmas/calcular-data-termino', [App\Http\Controllers\Api\TurmaController::class, 'calcularDataTermino']);
Route::post('/ambientes/{ambiente}/toggle-status', [App\Http\Controllers\Api\AmbienteController::class, 'toggleStatus']);
Route::post('/cursos/{curso}/toggle-status', [App\Http\Controllers\Api\CursoController::class, 'toggleStatus']);
Route::post('/colaboradores/{colaboradore}/toggle-status', [App\Http\Controllers\Api\ColaboradoreController::class, 'toggleStatus']);

Route::post('/login', [App\Http\Controllers\Api\LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Rota para fazer logout (revogar o token)
    Route::post('/logout', [App\Http\Controllers\Api\LoginController::class, 'logout']);

    // Rota de exemplo para buscar dados do usuário logado
    Route::get('/colaboradores-logados', function (Request $request) {
        return $request->user();
    });
});

Route::put('/colaboradores/{colaboradore}/update-nivel', [App\Http\Controllers\Api\ColaboradoreController::class, 'updateNivel']);
Route::put('/turmas/{turma}/update-nome', [App\Http\Controllers\Api\TurmaController::class, 'updateNome']);
Route::get('/colaboradores/{colaboradore}/verificar-nivel', [App\Http\Controllers\Api\ColaboradoreController::class, 'verificarNivel']);
Route::get('ambientes/ambientes-disponiveis', [App\Http\Controllers\Api\AmbienteController::class, 'getAmbientesDisponiveis']);
Route::patch('/turmas/{turma}/update-ambiente', [App\Http\Controllers\Api\TurmaController::class, 'updateAmbiente']);
Route::get('/gerar-pdf', [App\Http\Controllers\Api\pdfController::class, 'gerarPdf'])->name('gerar.pdf');
Route::get('/gerar-csv', [App\Http\Controllers\Api\csvController::class, 'gerarCsv'])->name('gerar.csv');
