<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmbienteRequest extends FormRequest
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
            'nome_ambiente' => 'required|string|max:220',
            'num_ambiente' => 'nullable|integer',
            'capacidade_ambiente' => 'required|integer',
            'tipo_ambiente_id' => 'required|integer|exists:tipos_ambientes,id',
            'status_ambiente' => 'sometimes|boolean', // `boolean` é melhor que `in:0,1`
        ];

        // --- A LÓGICA PRINCIPAL ESTÁ AQUI ---
        // Se o método do pedido for PUT ou PATCH (ou seja, um UPDATE)...
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // ...transformamos as regras 'required' em 'sometimes'.
            // Isto torna todos os campos opcionais na atualização.

            $regras['nome_ambiente'] = 'sometimes|required|string|max:220';
            $regras['capacidade_ambiente'] = 'sometimes|required|integer';
            $regras['tipo_ambiente_id'] = 'sometimes|required|integer|exists:tipos_ambientes,id';
        }

        return $regras;
    }
}
