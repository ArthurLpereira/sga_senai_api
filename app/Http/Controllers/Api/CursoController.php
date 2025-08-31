<?php

namespace App\Http\Controllers\Api;

use App\Models\Curso;
use Illuminate\Http\Request;
use App\Http\Requests\CursoRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CursoResource;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ALTERAÇÃO: Adicionado o with() para carregar o relacionamento da categoria
        $cursos = Curso::with('categoriasCurso')->paginate(15);

        return CursoResource::collection($cursos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CursoRequest $request): JsonResponse
    {
        $curso = Curso::create($request->validated());

        // ALTERAÇÃO: Carrega o relacionamento no objeto recém-criado
        $curso->load('categoriasCurso');

        return response()->json(new CursoResource($curso), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Curso $curso): JsonResponse
    {
        // ALTERAÇÃO: Carrega o relacionamento no objeto encontrado
        $curso->load('categoriasCurso');

        return response()->json(new CursoResource($curso));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CursoRequest $request, Curso $curso): JsonResponse
    {
        $curso->update($request->validated());

        // ALTERAÇÃO: Carrega o relacionamento no objeto atualizado
        $curso->load('categoriasCurso');

        return response()->json(new CursoResource($curso));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Curso $curso): Response
    {
        $curso->delete();

        return response()->noContent();
    }
}
