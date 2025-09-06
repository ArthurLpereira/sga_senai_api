<?php

namespace App\Http\Controllers\Api;

use App\Models\ColaboradoresHasTurma;
use Illuminate\Http\Request;
use App\Http\Requests\ColaboradoresHasTurmaRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColaboradoresHasTurmaResource;

class ColaboradoresHasTurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $colaboradoresHasTurmas = ColaboradoresHasTurma::paginate();

        return ColaboradoresHasTurmaResource::collection($colaboradoresHasTurmas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ColaboradoresHasTurmaRequest $request): JsonResponse
    {
        $colaboradoresHasTurma = ColaboradoresHasTurma::create($request->validated());

        return response()->json(new ColaboradoresHasTurmaResource($colaboradoresHasTurma));
    }

    /**
     * Display the specified resource.
     */
    public function show(ColaboradoresHasTurma $colaboradoresHasTurma): JsonResponse
    {
        return response()->json(new ColaboradoresHasTurmaResource($colaboradoresHasTurma));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ColaboradoresHasTurmaRequest $request, ColaboradoresHasTurma $colaboradoresHasTurma): JsonResponse
    {
        $colaboradoresHasTurma->update($request->validated());

        return response()->json(new ColaboradoresHasTurmaResource($colaboradoresHasTurma));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(ColaboradoresHasTurma $colaboradoresHasTurma): Response
    {
        $colaboradoresHasTurma->delete();

        return response()->noContent();
    }
}
