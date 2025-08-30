<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AlteracoesTurma
 *
 * @property $id
 * @property $data_hora_alteracao_turma
 * @property $descricao_alteracao_turma
 * @property $justificativa_alteracao_turma
 * @property $colaborador_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Colaboradore $colaboradore
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AlteracoesTurma extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['data_hora_alteracao_turma', 'descricao_alteracao_turma', 'justificativa_alteracao_turma', 'colaborador_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function colaboradore()
    {
        return $this->belongsTo(\App\Models\Colaboradore::class, 'colaborador_id', 'id');
    }
    
}
