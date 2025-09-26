<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColaboradoreRequest extends FormRequest
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
        $regras = [
            'nome_colaborador' => 'required|string',
            'email_colaborador' => 'required|string',
            'senha_colaborador' => 'required|string',
            'especialidade_colaborador' => 'required|string',
            'cor_colaborador' => 'required|string',
            'tipo_colaborador_id' => 'sometimes|exists:tipos_colaboradores,id',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $regras['nome_colaborador'] = 'sometimes|required|string';
            $regras['email_colaborador'] = 'sometimes|required|string';
            $regras['senha_colaborador'] = 'sometimes|required|string';
            $regras['especialidade_colaborador'] = 'sometimes|required|string';
            $regras['cor_colaborador'] = 'sometimes|required|string';
        }

        return $regras;
    }
}
