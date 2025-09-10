<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmbienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Garante que o nome é uma string com no máximo 220 caracteres.
            'nome_ambiente' => 'required|string|max:220',

            // Permite que o número seja opcional, mas se for enviado, deve ser um inteiro.
            'num_ambiente' => 'nullable|integer',

            // Garante que a capacidade é um número inteiro.
            'capacidade_ambiente' => 'required|integer',

            // Garante que o tipo de ambiente é um inteiro e que o ID enviado
            // realmente existe na tabela 'tipos_ambientes' na coluna 'id'.
            // Isto previne erros de chave estrangeira na base de dados.
            'tipo_ambiente_id' => 'required|integer|exists:tipos_ambientes,id',

            // O status é opcional. Se for enviado, deve ser '0' ou '1'.
            'status_ambiente' => 'sometimes|in:0,1',
        ];
    }
}
