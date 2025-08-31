<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CursoResource extends JsonResource
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
            'Nome do curso:' => $this->nome_curso,
            'Cor do curso:' => $this->cor_curso,
            'Preço:' => $this->valor_curso,

            'Categoria:' => $this->whenLoaded('categoriasCurso', function () {
                return $this->categoriasCurso->nome_categoria_curso;
            })
        ];
    }
}
