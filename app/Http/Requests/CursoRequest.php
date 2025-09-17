<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CursoRequest extends FormRequest
{
    /**
     * Determina se o utilizador está autorizado a fazer este pedido.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtém as regras de validação que se aplicam ao pedido.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Define as regras base que são comuns ou usadas na criação (POST)
        $regras = [
            'nome_curso' => 'required|string|max:220',
            'cor_curso' => 'required|string|max:45',
            'valor_curso' => 'required|numeric|min:0',
            'categoria_curso_id' => 'required|integer|exists:categorias_cursos,id',
            'status_curso' => 'sometimes|boolean',
            'carga_horaria_curso' => 'required|integer|min:1',
        ];

        // Se for um pedido de UPDATE (PUT/PATCH), torna os campos obrigatórios em opcionais.
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $regras['nome_curso'] = 'sometimes|required|string|max:220';
            $regras['cor_curso'] = 'sometimes|required|string|max:45';
            $regras['valor_curso'] = 'sometimes|required|numeric|min:0';
            $regras['categoria_curso_id'] = 'sometimes|required|integer|exists:categorias_cursos,id';
        }

        return $regras;
    }
}
