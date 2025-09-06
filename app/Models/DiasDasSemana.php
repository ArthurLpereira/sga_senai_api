<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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


    public function turmas(): BelongsToMany
    {
        // Note que as duas últimas chaves estão invertidas em relação ao Model Turma.
        return $this->belongsToMany(
            Turma::class,
            'dias_das_semanas_has_turmas',      // 1. O nome da sua tabela pivot.
            'dia_da_semana_id',       // 2. A chave estrangeira DESTE model na pivot.
            'turma_id'                // 3. A chave estrangeira do OUTRO model na pivot.
        );
    }
}
