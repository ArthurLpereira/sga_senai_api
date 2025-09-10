<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
