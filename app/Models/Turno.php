<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Turno
 *
 * @property $id
 * @property $nome_turno
 * @property $created_at
 * @property $updated_at
 *
 * @property Turma[] $turmas
 * @property Turma[] $turmas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Turno extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_turno'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    public function turmas()
    {
        return $this->hasMany(\App\Models\Turma::class, 'id', 'turno_id');
    }
}
