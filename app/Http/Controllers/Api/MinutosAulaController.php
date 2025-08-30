<?php

namespace App\Http\Controllers\Api;

use App\Models\MinutosAula;
use Illuminate\Http\Request;
use App\Http\Requests\MinutosAulaRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\MinutosAulaResource;

class MinutosAulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $minutosAulas = MinutosAula::paginate();

        return MinutosAulaResource::collection($minutosAulas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MinutosAulaRequest $request): JsonResponse
    {
        $minutosAula = MinutosAula::create($request->validated());

        return response()->json(new MinutosAulaResource($minutosAula));
    }

    /**
     * Display the specified resource.
     */
    public function show(MinutosAula $minutosAula): JsonResponse
    {
        return response()->json(new MinutosAulaResource($minutosAula));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MinutosAulaRequest $request, MinutosAula $minutosAula): JsonResponse
    {
        $minutosAula->update($request->validated());

        return response()->json(new MinutosAulaResource($minutosAula));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(MinutosAula $minutosAula): Response
    {
        $minutosAula->delete();

        return response()->noContent();
    }
}
