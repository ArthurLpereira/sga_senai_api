<?php

namespace App\Http\Controllers\Api;

use App\Models\CategoriasCurso;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriasCursoRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriasCursoResource;

class CategoriasCursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categoriasCursos = CategoriasCurso::paginate();

        return CategoriasCursoResource::collection($categoriasCursos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriasCursoRequest $request): JsonResponse
    {
        $categoriasCurso = CategoriasCurso::create($request->validated());

        return response()->json(new CategoriasCursoResource($categoriasCurso));
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriasCurso $categoriasCurso): JsonResponse
    {
        return response()->json(new CategoriasCursoResource($categoriasCurso));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriasCursoRequest $request, CategoriasCurso $categoriasCurso): JsonResponse
    {
        $categoriasCurso->update($request->validated());

        return response()->json(new CategoriasCursoResource($categoriasCurso));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(CategoriasCurso $categoriasCurso): Response
    {
        $categoriasCurso->delete();

        return response()->noContent();
    }
}
