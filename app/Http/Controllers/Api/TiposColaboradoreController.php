<?php

namespace App\Http\Controllers\Api;

use App\Models\TiposColaboradore;
use Illuminate\Http\Request;
use App\Http\Requests\TiposColaboradoreRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TiposColaboradoreResource;

class TiposColaboradoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tiposColaboradores = TiposColaboradore::paginate();

        return TiposColaboradoreResource::collection($tiposColaboradores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TiposColaboradoreRequest $request): JsonResponse
    {
        $tiposColaboradore = TiposColaboradore::create($request->validated());

        return response()->json(new TiposColaboradoreResource($tiposColaboradore));
    }

    /**
     * Display the specified resource.
     */
    public function show(TiposColaboradore $tiposColaboradore): JsonResponse
    {
        return response()->json(new TiposColaboradoreResource($tiposColaboradore));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TiposColaboradoreRequest $request, TiposColaboradore $tiposColaboradore): JsonResponse
    {
        $tiposColaboradore->update($request->validated());

        return response()->json(new TiposColaboradoreResource($tiposColaboradore));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(TiposColaboradore $tiposColaboradore): Response
    {
        $tiposColaboradore->delete();

        return response()->noContent();
    }
}
