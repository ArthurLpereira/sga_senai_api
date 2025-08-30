<?php

namespace App\Http\Controllers\Api;

use App\Models\DiasNaoLetivo;
use Illuminate\Http\Request;
use App\Http\Requests\DiasNaoLetivoRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiasNaoLetivoResource;

class DiasNaoLetivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $diasNaoLetivos = DiasNaoLetivo::paginate();

        return DiasNaoLetivoResource::collection($diasNaoLetivos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiasNaoLetivoRequest $request): JsonResponse
    {
        $diasNaoLetivo = DiasNaoLetivo::create($request->validated());

        return response()->json(new DiasNaoLetivoResource($diasNaoLetivo));
    }

    /**
     * Display the specified resource.
     */
    public function show(DiasNaoLetivo $diasNaoLetivo): JsonResponse
    {
        return response()->json(new DiasNaoLetivoResource($diasNaoLetivo));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiasNaoLetivoRequest $request, DiasNaoLetivo $diasNaoLetivo): JsonResponse
    {
        $diasNaoLetivo->update($request->validated());

        return response()->json(new DiasNaoLetivoResource($diasNaoLetivo));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(DiasNaoLetivo $diasNaoLetivo): Response
    {
        $diasNaoLetivo->delete();

        return response()->noContent();
    }
}
