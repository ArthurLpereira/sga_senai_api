<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TurmaRequest;
use App\Http\Resources\TurmaResource;
use App\Models\Turma;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\CalculoDataTerminoService;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    /**
     * Mostra uma lista de todas as turmas.
     */
    public function index()
    {
        // Carrega todos os relacionamentos necessários para a lista
        $turmas = Turma::with(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana'])->paginate(15);
        return TurmaResource::collection($turmas);
    }

    /**
     * Guarda uma nova turma E liga os seus relacionamentos na tabela pivot.
     */
    public function store(TurmaRequest $request): JsonResponse
    {
        // 2. Usamos uma transação para garantir a integridade dos dados.
        $turma = DB::transaction(function () use ($request) {

            // 3. Primeiro, cria a turma principal com os dados validados.
            $novaTurma = Turma::create($request->validated());

            // 4. Verifica se os IDs dos colaboradores foram enviados na requisição.
            if ($request->has('colaboradores_ids')) {
                // 5. Usa o método attach() para criar os registos na tabela pivot.
                $novaTurma->colaboradores()->attach($request->colaboradores_ids);
            }

            // 6. Adiciona a mesma lógica para os dias da semana.
            if ($request->has('dias_da_semana_ids')) {
                $novaTurma->diasDaSemana()->attach($request->dias_da_semana_ids);
            }

            // Retorna a turma recém-criada
            return $novaTurma;
        });

        // Carrega todos os relacionamentos para a resposta ser completa
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana']);

        // Retorna a resposta formatada
        return response()->json(new TurmaResource($turma), 201);
    }

    /**
     * Mostra uma turma específica.
     */
    public function show(Turma $turma): JsonResponse
    {
        // Carrega todos os relacionamentos no objeto encontrado
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana']);
        return response()->json(new TurmaResource($turma));
    }

    /**
     * Atualiza uma turma existente E os seus relacionamentos na tabela pivot.
     */
    public function update(TurmaRequest $request, Turma $turma): JsonResponse
    {
        $turma = DB::transaction(function () use ($request, $turma) {
            // Atualiza os dados da tabela principal 'turmas'
            $turma->update($request->validated());

            // Para atualizações, usamos sync() em vez de attach().
            // O sync() apaga as ligações antigas e insere apenas as que vieram na requisição.
            // É a forma correta de atualizar um relacionamento Muitos-para-Muitos.
            if ($request->has('colaboradores_ids')) {
                $turma->colaboradores()->sync($request->colaboradores_ids);
            }

            if ($request->has('dias_da_semana_ids')) {
                $turma->diasDaSemana()->sync($request->dias_da_semana_ids);
            }

            return $turma;
        });

        // Carrega os relacionamentos para a resposta ser completa
        $turma->load(['curso', 'ambiente', 'statusTurma', 'minutosAula', 'turno', 'colaboradores', 'diasDaSemana']);

        return response()->json(new TurmaResource($turma));
    }

    /**
     * Apaga uma turma.
     */
    public function destroy(Turma $turma): Response
    {
        $turma->delete();
        return response()->noContent();
    }

    public function calcularDataTermino(Request $request, CalculoDataTerminoService $calculoService): JsonResponse
    {
        // 2. Valida os dados de entrada que vêm do formulário (Postman).
        $validator = Validator::make($request->all(), [
            'data_inicio_turma' => 'required|date_format:Y-m-d',
            'carga_horaria_total' => 'required|integer|min:1', // Carga total em horas
            'duracao_aula_minutos' => 'required|integer|min:1', // Duração da aula em minutos
            'dias_da_semana_ids' => 'required|array|min:1',
            'dias_da_semana_ids.*' => 'exists:dias_das_semanas,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dados = $validator->validated();

        // 3. Chama o nosso serviço para fazer o trabalho pesado.
        $dataTermino = $calculoService->calcular(
            $dados['data_inicio_turma'],
            $dados['carga_horaria_total'],
            $dados['duracao_aula_minutos'],
            $dados['dias_da_semana_ids']
        );

        // 4. Retorna a data calculada como uma resposta JSON.
        return response()->json([
            'data_termino_calculada' => $dataTermino,
        ]);
    }
}
