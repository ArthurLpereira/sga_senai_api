<?php

namespace App\Models;

// 1. GARANTA QUE ESTA LINHA ESTÁ PRESENTE
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// Se você ainda não fez isso, mude "Model" para "Authenticatable" para o login funcionar
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens; // Para o login via API com token

// Mude "extends Model" para "extends Authenticatable"
class Colaboradore extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'nome_colaborador',
        'email_colaborador',
        'senha_colaborador',
        'especialidade_colaborador',
        'cor_colaborador',
        'status_colaborador',
        'tipo_colaborador_id',
    ];

    /**
     * 2. ESTA É A VERSÃO CORRETA E SEGURA
     * Usa o sistema de hash do Laravel (Bcrypt com salt)
     */
    public function setSenhaColaboradorAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['senha_colaborador'] = Hash::make($value);
        }
    }

    /**
     * Informa ao Laravel qual é a coluna da senha para autenticação
     */
    public function getAuthPassword()
    {
        return $this->senha_colaborador;
    }

    /** * Define o relacionamento: Um Colaborador pertence a (belongsTo) um TipoColaboradore.
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
        return $this->hasMany(AlteracoesTurma::class, 'colaborador_id', 'id');
    }

    /**
     * Define o relacionamento Muitos-para-Muitos com Turmas.
     */
    public function turmas(): BelongsToMany
    {
        return $this->belongsToMany(
            Turma::class,
            'colaboradores_has_turmas',
            'colaborador_id',
            'turma_id'
        );
    }
}
