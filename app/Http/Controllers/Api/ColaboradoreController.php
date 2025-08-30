<?php

namespace App\Http\Controllers\Api;

use App\Models\Colaboradore;
use Illuminate\Http\Request;
use App\Http\Requests\ColaboradoreRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColaboradoreResource;

class ColaboradoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $colaboradores = Colaboradore::paginate();

        return ColaboradoreResource::collection($colaboradores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ColaboradoreRequest $request): JsonResponse
    {
        $colaboradore = Colaboradore::create($request->validated());

        return response()->json(new ColaboradoreResource($colaboradore));
    }

    /**
     * Display the specified resource.
     */
    public function show(Colaboradore $colaboradore): JsonResponse
    {
        return response()->json(new ColaboradoreResource($colaboradore));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ColaboradoreRequest $request, Colaboradore $colaboradore): JsonResponse
    {
        $colaboradore->update($request->validated());

        return response()->json(new ColaboradoreResource($colaboradore));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Colaboradore $colaboradore): Response
    {
        $colaboradore->delete();

        return response()->noContent();
    }
}
