<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DiasDasSemana
 *
 * @property $id
 * @property $nome_dia_da_semana
 * @property $created_at
 * @property $updated_at
 *
 * @property DiasDaSemanasHasTurma[] $diasDaSemanasHasTurmas
 * @property DiasDasSemanasHasTurma[] $diasDasSemanasHasTurmas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DiasDasSemana extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_dia_da_semana'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function diasDaSemanasHasTurmas()
    // {
    //     return $this->hasMany(\App\Models\DiasDaSemanasHasTurma::class, 'id_dia_da_semana', 'dias_da_semanas_id_dias_da_semana');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function diasDasSemanasHasTurmas()
    // {
    //     return $this->hasMany(\App\Models\DiasDasSemanasHasTurma::class, 'id', 'dia_da_semana_id');
    // }

}
