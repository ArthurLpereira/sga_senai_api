<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TurmaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'nome_turma' => $this->nome_turma,
        //     'capacidade_turma' => $this->capacidade_turma,
        //     'data_inicio_turma' => $this->data_inicio_turma,
        //     'data_termino_turma:' => $this->data_termino_turma,

        //     'Curso:' => $this->whenLoaded('curso', function () {
        //         return $this->curso->nome_curso;
        //     }),

        //     'Ambiente:' => $this->whenLoaded('ambiente', function () {
        //         return $this->ambiente->nome_ambiente;
        //     }),

        //     'Status:' => $this->whenLoaded('statusTurma', function () {
        //         return $this->statusTurma->nome_status_turma;
        //     }),

        //     'Status:' => $this->whenLoaded('minutosAula', function () {
        //         return $this->minutosAula->quant_minuto_aula;
        //     }),

        //     'Turno:' => $this->whenLoaded('turno', function () {
        //         return $this->turno->nome_turno;
        //     }),

        //     'colaboradores' => ColaboradoreResource::collection($this->whenLoaded('colaboradores')),
        //     'dias_da_semana' => DiasDasSemanaResource::collection($this->whenLoaded('diasDaSemana')),


        // ];
    }
}
