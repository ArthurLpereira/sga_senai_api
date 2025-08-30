<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StatusTurma
 *
 * @property $id
 * @property $nome_status_turma
 * @property $created_at
 * @property $updated_at
 *
 * @property Turma[] $turmas
 * @property Turma[] $turmas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class StatusTurma extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_status_turma'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */


    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    public function turmas()
    {
        return $this->hasMany(\App\Models\Turma::class, 'id', 'status_turma_id');
    }
}
