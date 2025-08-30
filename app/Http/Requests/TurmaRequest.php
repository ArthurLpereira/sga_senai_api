<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TurmaRequest extends FormRequest
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
			'nome_turma' => 'required|string',
			'capacidade_turma' => 'required',
			'data_inicio_turma' => 'required',
			'data_termino_turma' => 'required',
			'curso_id' => 'required',
			'ambiente_id' => 'required',
			'status_turma_id' => 'required',
			'minuto_aula_id' => 'required',
			'turno_id' => 'required',
        ];
    }
}
