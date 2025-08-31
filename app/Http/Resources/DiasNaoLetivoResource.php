<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiasNaoLetivoResource extends JsonResource
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
            'Data:' => $this->data_dia_nao_letivo,
            'Descricão:' => $this->descricao_dia_nao_letivo,
            'Tipo de feriado:' => $this->tipo_feriado_dia_nao_letivo,
        ];
    }
}
