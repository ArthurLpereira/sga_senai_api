<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MinutosAula
 *
 * @property $id
 * @property $quant_minuto_aula
 * @property $created_at
 * @property $updated_at
 *
 * @property Turma[] $turmas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class MinutosAula extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['quant_minuto_aula'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function turmas()
    {
        return $this->hasMany(\App\Models\Turma::class, 'id', 'minuto_aula_id');
    }
}
