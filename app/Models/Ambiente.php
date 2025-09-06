<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Ambiente
 *
 * @property $id
 * @property $nome_ambiente
 * @property $num_ambiente
 * @property $capacidade_ambiente
 * @property $status_ambiente
 * @property $tipo_ambiente_id
 * @property $created_at
 * @property $updated_at
 *
 * @property TipoAmbiente $tipoAmbiente
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
    protected $fillable = [
        'nome_ambiente',
        'num_ambiente',
        'capacidade_ambiente',
        'status_ambiente',
        'tipo_ambiente_id',
    ];

    /**
     * Define a relação de que um Ambiente PERTENCE A UM TipoAmbiente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoAmbiente(): BelongsTo
    {
        // Laravel vai procurar por 'tipo_ambiente_id' na tabela 'ambientes' por padrão.
        return $this->belongsTo(TiposAmbiente::class);
    }

    /**
     * Define a relação de que um Ambiente PODE TER VÁRIAS Turmas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function turmas(): HasMany
    {
        // O segundo argumento é a chave estrangeira na tabela 'turmas' (ambiente_id).
        // O terceiro é a chave local na tabela 'ambientes' (id).
        // Se seguir a convenção do Laravel (ambiente_id), não precisa passar os argumentos.
        return $this->hasMany(Turma::class, 'ambiente_id', 'id');
    }
}
