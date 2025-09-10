<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlteracoesTurmaResource extends JsonResource
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
        //     'data_hora_alteracao_turma' => $this->data_hora_alteracao_turma,
        //     'descricao_alteracao_turma' => $this->descricao_alteracao_turma,
        //     'justificativa_alteracao_turma' => $this->justificativa_alteracao_turma,

        //     'colaborador_id' => $this->whenLoaded('colaboradore', function () {
        //         return $this->colaboradore->nome_colaborador;
        //     })
        // ];
    }
}
