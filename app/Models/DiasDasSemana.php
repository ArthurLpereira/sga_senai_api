<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DiasDasSemana extends Model
{
    use HasFactory;

    // --- Configuração Essencial do Model ---
    // Como a sua chave primária é 'id', não precisamos de a definir.

    protected $table = 'dias_das_semanas'; // O nome da sua tabela é no plural

    protected $fillable = ['nome_dia_da_semana'];

    /**
     * Define o relacionamento INVERSO Muitos-para-Muitos com Turmas.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function turmas(): BelongsToMany
    {
        // Note que as duas últimas chaves estão invertidas em relação ao Model Turma.
        return $this->belongsToMany(
            Turma::class,
            'dia_da_semana_turma',
            'dia_da_semana_id',
            'turma_id'
        );
    }
}
