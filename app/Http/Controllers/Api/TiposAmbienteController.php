<?php

namespace App\Http\Controllers\Api;

use App\Models\TiposAmbiente;
use Illuminate\Http\Request;
use App\Http\Requests\TiposAmbienteRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TiposAmbienteResource;

class TiposAmbienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tiposAmbientes = TiposAmbiente::paginate();

        return TiposAmbienteResource::collection($tiposAmbientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TiposAmbienteRequest $request): JsonResponse
    {
        $tiposAmbiente = TiposAmbiente::create($request->validated());

        return response()->json(new TiposAmbienteResource($tiposAmbiente));
    }

    /**
     * Display the specified resource.
     */
    public function show(TiposAmbiente $tiposAmbiente): JsonResponse
    {
        return response()->json(new TiposAmbienteResource($tiposAmbiente));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TiposAmbienteRequest $request, TiposAmbiente $tiposAmbiente): JsonResponse
    {
        $tiposAmbiente->update($request->validated());

        return response()->json(new TiposAmbienteResource($tiposAmbiente));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(TiposAmbiente $tiposAmbiente): Response
    {
        $tiposAmbiente->delete();

        return response()->noContent();
    }
}
