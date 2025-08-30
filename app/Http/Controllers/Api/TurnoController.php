<?php

namespace App\Http\Controllers\Api;

use App\Models\Turno;
use Illuminate\Http\Request;
use App\Http\Requests\TurnoRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TurnoResource;

class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $turnos = Turno::paginate();

        return TurnoResource::collection($turnos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TurnoRequest $request): JsonResponse
    {
        $turno = Turno::create($request->validated());

        return response()->json(new TurnoResource($turno));
    }

    /**
     * Display the specified resource.
     */
    public function show(Turno $turno): JsonResponse
    {
        return response()->json(new TurnoResource($turno));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TurnoRequest $request, Turno $turno): JsonResponse
    {
        $turno->update($request->validated());

        return response()->json(new TurnoResource($turno));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Turno $turno): Response
    {
        $turno->delete();

        return response()->noContent();
    }
}
