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
        return [
            'id:' => $this->id,
            'Nome do ambiente:' => $this->nome_ambiente,
            'Número do ambiente:' => $this->num_ambiente,
            'Capacidade do ambiente:' => $this->capacidade_ambiente,
            'Status do ambiente:' => $this->status_ambiente,
        ];
    }
}
