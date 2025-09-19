<?php

namespace App\Http\Controllers\Api;

use App\Models\Colaboradore;
use Illuminate\Http\Request;
use App\Http\Requests\ColaboradoreRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColaboradoreResource;
use Illuminate\Support\Facades\Hash;

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

    public function toggleStatus(Colaboradore $colaboradore): ColaboradoreResource
    {
        // 1. Lógica de Alternância (Toggle):
        $colaboradore->status_colaborador = ($colaboradore->status_colaborador == '1') ? '0' : '1';

        // 2. Guarda a alteração na base de dados.
        $colaboradore->save();

        // 3. Resposta: Retorna o colaborador completo e atualizado, formatado pelo Resource.
        //    O `load` garante que o nome do tipo de colaborador é carregado para a resposta.
        return new ColaboradoreResource($colaboradore->load('tiposColaboradore'));
    }

    public function updateNivel(Request $request, Colaboradore $colaboradore): JsonResponse
    {
        // Validação CORRIGIDA e MELHORADA
        $request->validate([
            'tipo_colaborador_id' => 'required|exists:tipos_colaboradores,id',
        ]);

        // CORREÇÃO: Atualize a coluna correta (a chave estrangeira).
        $colaboradore->tipo_colaborador_id = $request->input('tipo_colaborador_id');

        // Salva a alteração no banco de dados.
        $colaboradore->save();

        // Retorna o recurso do colaborador atualizado.
        return response()->json(new ColaboradoreResource($colaboradore));
    }

    public function verificarNivel(Colaboradore $colaboradore): JsonResponse
    {
        // Carrega a relação 'tiposColaboradore' para acessar o nome do tipo.
        $colaboradore->load('tiposColaboradore');

        // Retorna o nome do tipo de colaborador.
        return response()->json([
            'tipo_colaborador' => $colaboradore->tiposColaboradore->nome_tipo_colaborador,
        ]);
    }
}
