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
        // //return parent::toArray($request);

        return [
            'id:' => $this->id,
            'Hora da alteração:' => $this->data_hora_alteracao_turma,
            'Descrição da mudança:' => $this->descricao_alteracao_turma,
            'Justificativa da mudança:' => $this->justificativa_alteracao_turma,

            'Colaborador:' => $this->whenLoaded('colaboradore', function () {
                return $this->colaboradore->nome_colaborador;
            })
        ];
    }
}
