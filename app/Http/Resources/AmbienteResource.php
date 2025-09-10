<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmbienteResource extends JsonResource
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
        //     'nome_ambiente' => $this->nome_ambiente,
        //     'num_ambiente' => $this->num_ambiente,
        //     'capacidade_ambiente' => $this->capacidade_ambiente,
        //     'status_ambiente' => $this->status_ambiente,

        //     'tipo_ambiente_id' => $this->whenLoaded('tipoAmbiente', function () {
        //         return $this->tipoAmbiente->nome_tipo_ambiente;
        //     })
        // ];
    }
}
