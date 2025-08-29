<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ambiente
 *
 * @property $id
 * @property $nome_ambiente
 * @property $num_ambiente
 * @property $capacidade_ambiente
 * @property $status_ambiente
 * @property $created_at
 * @property $updated_at
 *
 * @property Turma[] $turmas
 * @property Turma[] $turmas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ambiente extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_ambiente', 'num_ambiente', 'capacidade_ambiente', 'status_ambiente', 'id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function turmas()
    // {
    //     return $this->hasMany(\App\Models\Turma::class, 'id_ambiente', 'Ambientes_id_ambiente');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function turmas()
    // {
    //     return $this->hasMany(\App\Models\Turma::class, 'id_ambiente', 'ambiente_id');
    // }

}
