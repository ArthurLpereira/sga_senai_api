<?php

namespace App\Http\Controllers\Api;

use App\Models\Turma;
use Illuminate\Http\Request;
use App\Http\Requests\TurmaRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TurmaResource;

class TurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Carrega todos os relacionamentos necessários para a lista de turmas
        $turmas = Turma::with(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno'])->paginate(15);

        return TurmaResource::collection($turmas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TurmaRequest $request): JsonResponse
    {
        $turma = Turma::create($request->validated());

        // Carrega os relacionamentos no objeto recém-criado
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno']);

        return response()->json(new TurmaResource($turma), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Turma $turma): JsonResponse
    {
        // Carrega os relacionamentos no objeto encontrado
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno']);

        return response()->json(new TurmaResource($turma));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TurmaRequest $request, Turma $turma): JsonResponse
    {
        $turma->update($request->validated());

        // Carrega os relacionamentos no objeto atualizado
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno']);

        return response()->json(new TurmaResource($turma));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Turma $turma): Response
    {
        $turma->delete();

        return response()->noContent();
    }
}
