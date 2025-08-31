<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColaboradoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id:' => $this->id,
            'nome:' => $this->nome_colaborador,
            'email:' => $this->email_colaborador,
            'especialidade:' => $this->especialidade_colaborador,

            'tipo:' => $this->whenLoaded('tiposColaboradore', function () {
                return $this->tiposColaboradore->nome_tipo_colaborador;
            })
        ];
    }
}
