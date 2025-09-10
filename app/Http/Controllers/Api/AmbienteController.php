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
    public function index()
    {
        // Adicionado: .with('tipoAmbiente') para carregar a relação (Eager Loading)
        $ambientes = Ambiente::with('tipoAmbiente')->paginate();

        return AmbienteResource::collection($ambientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AmbienteRequest $request): AmbienteResource
    {
        $ambiente = Ambiente::create($request->validated());

        // Adicionado: .load('tipoAmbiente') para carregar a relação no modelo recém-criado
        $ambiente->load('tipoAmbiente');

        // Retornar o resource diretamente ajusta o status HTTP para 201 Created automaticamente
        return new AmbienteResource($ambiente);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ambiente $ambiente): AmbienteResource
    {
        // Removido: A linha '$ambiente = Ambiente::find($ambiente);' era redundante e incorreta
        // O Laravel já injeta o objeto $ambiente correto (Route-Model Binding)

        // Adicionado: .load('tipoAmbiente') para carregar a relação
        $ambiente->load('tipoAmbiente');

        return new AmbienteResource($ambiente);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AmbienteRequest $request, Ambiente $ambiente): AmbienteResource
    {
        $ambiente->update($request->validated());

        // Adicionado: .load('tipoAmbiente') para carregar a relação no modelo atualizado
        $ambiente->load('tipoAmbiente');

        return new AmbienteResource($ambiente);
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Ambiente $ambiente): Response
    {
        $ambiente->delete();

        return response()->noContent();
    }

    public function toggleStatus(Ambiente $ambiente): AmbienteResource
    {
        // 1. Lógica de Alternância (Toggle):
        $ambiente->status_ambiente = ($ambiente->status_ambiente == '1') ? '0' : '1';

        // 2. Guarda a alteração na base de dados.
        $ambiente->save();

        // 3. Resposta: Retorna o ambiente completo e atualizado, formatado pelo Resource.
        //    O `load` garante que o nome do tipo de ambiente é carregado para a resposta.
        return new AmbienteResource($ambiente->load('tipoAmbiente'));
    }
}
