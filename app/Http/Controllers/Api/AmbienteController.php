<?php

namespace App\Http\Controllers\Api;

use App\Models\Ambiente;
use Illuminate\Http\Request;
use App\Http\Requests\AmbienteRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AmbienteResource;

class AmbienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ambientes = Ambiente::paginate();

        return AmbienteResource::collection($ambientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AmbienteRequest $request): JsonResponse
    {
        $ambiente = Ambiente::create($request->validated());

        return response()->json(new AmbienteResource($ambiente));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ambiente $ambiente): JsonResponse
    {
        $ambiente = Ambiente::find($ambiente);
        return response()->json(new AmbienteResource($ambiente));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AmbienteRequest $request, Ambiente $ambiente): JsonResponse
    {
        $ambiente->update($request->validated());

        return response()->json(new AmbienteResource($ambiente));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Ambiente $ambiente): Response
    {
        $ambiente->delete();

        return response()->noContent();
    }
}
