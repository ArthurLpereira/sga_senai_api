<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiasNaoLetivoRequest extends FormRequest
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
			'data_dia_nao_letivo' => 'required',
			'descricao_dia_nao_letivo' => 'required|string',
			'tipo_feriado_dia_nao_letivo' => 'required',
        ];
    }
}
