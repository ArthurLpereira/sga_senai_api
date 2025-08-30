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
        $turmas = Turma::paginate();

        return TurmaResource::collection($turmas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TurmaRequest $request): JsonResponse
    {
        $turma = Turma::create($request->validated());

        return response()->json(new TurmaResource($turma));
    }

    /**
     * Display the specified resource.
     */
    public function show(Turma $turma): JsonResponse
    {
        return response()->json(new TurmaResource($turma));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TurmaRequest $request, Turma $turma): JsonResponse
    {
        $turma->update($request->validated());

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
