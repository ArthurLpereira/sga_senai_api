<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ColaboradoresHasTurma
 *
 * @property $colaborador_id
 * @property $turma_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Colaboradore $colaboradore
 * @property Turma $turma
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ColaboradoresHasTurma extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['colaborador_id', 'turma_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function colaboradore()
    {
        return $this->belongsTo(\App\Models\Colaboradore::class, 'colaborador_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function turma()
    {
        return $this->belongsTo(\App\Models\Turma::class, 'turma_id', 'id');
    }
}
