<?php

namespace App\Http\Controllers\Api;

use App\Models\DiasDasSemana;
use Illuminate\Http\Request;
use App\Http\Requests\DiasDasSemanaRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiasDasSemanaResource;

class DiasDasSemanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $diasDasSemanas = DiasDasSemana::paginate();

        return DiasDasSemanaResource::collection($diasDasSemanas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiasDasSemanaRequest $request): JsonResponse
    {
        $diasDasSemana = DiasDasSemana::create($request->validated());

        return response()->json(new DiasDasSemanaResource($diasDasSemana));
    }

    /**
     * Display the specified resource.
     */
    public function show(DiasDasSemana $diasDasSemana): JsonResponse
    {
        return response()->json(new DiasDasSemanaResource($diasDasSemana));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiasDasSemanaRequest $request, DiasDasSemana $diasDasSemana): JsonResponse
    {
        $diasDasSemana->update($request->validated());

        return response()->json(new DiasDasSemanaResource($diasDasSemana));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(DiasDasSemana $diasDasSemana): Response
    {
        $diasDasSemana->delete();

        return response()->noContent();
    }
}
