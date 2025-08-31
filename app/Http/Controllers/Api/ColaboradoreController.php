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
    public function index(Request $request)
    {
        $colaboradores = Colaboradore::with('tiposColaboradore')->paginate(15);
        return ColaboradoreResource::collection($colaboradores);
    }

    public function store(ColaboradoreRequest $request): JsonResponse
    {
        $colaboradore = Colaboradore::create($request->validated());

        $colaboradore->load('tiposColaboradore');

        return response()->json(new ColaboradoreResource($colaboradore), 201);
    }

    public function show(Colaboradore $colaboradore): JsonResponse
    {

        $colaboradore->load('tiposColaboradore');

        return response()->json(new ColaboradoreResource($colaboradore));
    }

    public function update(ColaboradoreRequest $request, Colaboradore $colaboradore): JsonResponse
    {
        $colaboradore->update($request->validated());
        $colaboradore->load('tiposColaboradore');

        return response()->json(new ColaboradoreResource($colaboradore));
    }

    public function destroy(Colaboradore $colaboradore): Response
    {
        $colaboradore->delete();

        return response()->noContent();
    }
}
