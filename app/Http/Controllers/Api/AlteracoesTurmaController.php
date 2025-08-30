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
        $alteracoesTurmas = AlteracoesTurma::paginate();

        return AlteracoesTurmaResource::collection($alteracoesTurmas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlteracoesTurmaRequest $request): JsonResponse
    {
        $alteracoesTurma = AlteracoesTurma::create($request->validated());

        return response()->json(new AlteracoesTurmaResource($alteracoesTurma));
    }

    /**
     * Display the specified resource.
     */
    public function show(AlteracoesTurma $alteracoesTurma): JsonResponse
    {
        return response()->json(new AlteracoesTurmaResource($alteracoesTurma));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlteracoesTurmaRequest $request, AlteracoesTurma $alteracoesTurma): JsonResponse
    {
        $alteracoesTurma->update($request->validated());

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
