<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Colaboradore extends Model
{
    use HasFactory;

    // A sua chave primária já é 'id', o que é perfeito.

    protected $fillable = [
        'nome_colaborador',
        'email_colaborador',
        'especialidade_colaborador',
        'status_colaborador',
        'tipo_colaborador_id',
    ];

    /**
     * Define o relacionamento: Um Colaborador pertence a (belongsTo) um TipoColaboradore.
     */
    public function tiposColaboradore(): BelongsTo
    {
        return $this->belongsTo(TiposColaboradore::class, 'tipo_colaborador_id', 'id');
    }

    /**
     * Define o relacionamento: Um Colaborador tem muitas (hasMany) AlteracoesTurma.
     */
    public function alteracoesTurmas(): HasMany
    {
        // Corrigido: A chave estrangeira na tabela 'alteracoes_turmas' e a chave local aqui.
        return $this->hasMany(AlteracoesTurma::class, 'colaborador_id', 'id');
    }

    /**
     * Define o relacionamento Muitos-para-Muitos com Turmas.
     */
    public function turmas(): BelongsToMany
    {
        return $this->belongsToMany(
            Turma::class,
            'colaboradores_has_turmas', // 1. Nome da tabela pivot
            'colaborador_id',    // 2. Chave estrangeira DESTE model na pivot
            'turma_id'           // 3. Chave estrangeira do OUTRO model na pivot
        );
    }
}
