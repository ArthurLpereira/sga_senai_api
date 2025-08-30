<?php

namespace App\Http\Controllers\Api;

use App\Models\StatusTurma;
use Illuminate\Http\Request;
use App\Http\Requests\StatusTurmaRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatusTurmaResource;

class StatusTurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statusTurmas = StatusTurma::paginate();

        return StatusTurmaResource::collection($statusTurmas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StatusTurmaRequest $request): JsonResponse
    {
        $statusTurma = StatusTurma::create($request->validated());

        return response()->json(new StatusTurmaResource($statusTurma));
    }

    /**
     * Display the specified resource.
     */
    public function show(StatusTurma $statusTurma): JsonResponse
    {
        return response()->json(new StatusTurmaResource($statusTurma));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StatusTurmaRequest $request, StatusTurma $statusTurma): JsonResponse
    {
        $statusTurma->update($request->validated());

        return response()->json(new StatusTurmaResource($statusTurma));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(StatusTurma $statusTurma): Response
    {
        $statusTurma->delete();

        return response()->noContent();
    }
}
