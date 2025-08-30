<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Colaboradore
 *
 * @property $id
 * @property $nome_colaborador
 * @property $email_colaborador
 * @property $especialidade_colaborador
 * @property $tipo_colaborador_id
 * @property $created_at
 * @property $updated_at
 *
 * @property TiposColaboradore $tiposColaboradore
 * @property AlteracaoTurma[] $alteracaoTurmas
 * @property ColaboradoresHasTurma[] $colaboradoresHasTurmas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Colaboradore extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_colaborador', 'email_colaborador', 'especialidade_colaborador', 'tipo_colaborador_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tiposColaboradore()
    {
        return $this->belongsTo(\App\Models\TiposColaboradore::class, 'tipo_colaborador_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alteracoesTurmas()
    {
        return $this->hasMany(\App\Models\alteracoesTurma::class, 'id', 'colaborador_id');
    }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function colaboradoresHasTurmas()
    // {
    //     return $this->hasMany(\App\Models\ColaboradoresHasTurma::class, 'id', 'colaborador_id');
    // }

}
