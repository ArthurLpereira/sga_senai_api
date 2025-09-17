<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TurmaResource; // Reutilizamos o TurmaResource para formatar a resposta
use App\Models\Turma;
use Illuminate\Http\Request;
use Carbon\Carbon; // A poderosa biblioteca de datas do Laravel

// Comando para criar este ficheiro: php artisan make:controller Api/InfoDiariaController
class InfoDiariaController extends Controller
{
    /**
     * Busca todas as informações detalhadas das turmas para um dia específico.
     * Este método é o "cérebro" da sua tela de "Ver Mais".
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getInfoDiarias(Request $request)
    {
        // 1. Valida a data recebida. Se o front-end não enviar uma data
        //    (ex: /api/info-diarias), ele usa a data de hoje como padrão.
        $request->validate(['data' => 'nullable|date_format:Y-m-d']);
        $data = Carbon::parse($request->input('data', 'today'));

        // 2. Converte a data (ex: 2025-09-17) para o ID do dia da semana
        //    correspondente na sua base de dados.
        //    O Carbon considera Domingo=0, Segunda=1, etc.
        //    A sua base de dados tem 1=Domingo, 2=Segunda, etc.
        //    Portanto, somamos 1 ao resultado do Carbon->dayOfWeek.
        $diaDaSemanaId = $data->dayOfWeek + 1;

        // 3. A Consulta Principal: Encontra todas as turmas que cumprem os critérios.
        $turmasDoDia = Turma::
            // A) Filtra turmas que estão ativas na data especificada.
            where('data_inicio_turma', '<=', $data->format('Y-m-d'))
            ->where('data_termino_turma', '>=', $data->format('Y-m-d'))

            // B) E que têm aula neste dia da semana específico.
            //    `whereHas` faz uma sub-consulta na tabela pivot ('dia_da_semana_turma')
            //    usando o relacionamento `diasDaSemana` que você definiu no Model Turma.
            ->whereHas('diasDaSemana', function ($query) use ($diaDaSemanaId) {
                // `dias_das_semanas.id` refere-se à chave primária na tabela `dias_das_semanas`.
                $query->where('dias_das_semanas.id', $diaDaSemanaId);
            })

            // C) Carrega todos os relacionamentos que o seu Model Turma conhece.
            //    Isto é o "Eager Loading". É a forma mais otimizada de buscar
            //    todos os dados de uma vez, evitando centenas de consultas à base de dados.
            ->with([
                'curso.categoriasCurso',        // Carrega o curso e a sua categoria
                'ambiente.tipoAmbiente',      // Carrega o ambiente e o seu tipo
                'statusTurma',
                'minutosAula',
                'turno',
                'colaboradores.tiposColaboradore' // CORREÇÃO AQUI: Usamos o nome exato do método no Model
            ])

            ->get();

        // 4. Retorna a coleção de turmas, formatada pelo seu TurmaResource.
        //    Cada turma na lista será "traduzida" pelo seu Resource antes de ser enviada.
        return TurmaResource::collection($turmasDoDia);
    }
}
