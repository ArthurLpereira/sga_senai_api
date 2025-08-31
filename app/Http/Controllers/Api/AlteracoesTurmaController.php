<?php

namespace App\Http\Controllers\Api;

use App\Models\AlteracoesTurma;
use Illuminate\Http\Request;
use App\Http\Requests\AlteracoesTurmaRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AlteracoesTurmaResource;

class AlteracoesTurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Carrega o relacionamento 'colaboradore' para a lista
        $alteracoesTurma = AlteracoesTurma::with('colaboradore')->paginate(15);
        return AlteracoesTurmaResource::collection($alteracoesTurma);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlteracoesTurmaRequest $request): JsonResponse
    {
        $alteracoesTurma = AlteracoesTurma::create($request->validated());

        // Carrega o relacionamento no objeto recém-criado
        $alteracoesTurma->load('colaboradore');

        return response()->json(new AlteracoesTurmaResource($alteracoesTurma), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AlteracoesTurma $alteracoesTurma): JsonResponse
    {
        // Carrega o relacionamento no objeto encontrado
        $alteracoesTurma->load('colaboradore');

        return response()->json(new AlteracoesTurmaResource($alteracoesTurma));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlteracoesTurmaRequest $request, AlteracoesTurma $alteracoesTurma): JsonResponse
    {
        $alteracoesTurma->update($request->validated());

        // Carrega o relacionamento no objeto atualizado
        $alteracoesTurma->load('colaboradore');

        return response()->json(new AlteracoesTurmaResource($alteracoesTurma));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(AlteracoesTurma $alteracoesTurma): Response
    {
        $alteracoesTurma->delete();

        return response()->noContent();
    }
}
