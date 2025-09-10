<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Curso
 *
 * @property $id
 * @property $nome_curso
 * @property $cor_curso
 * @property $valor_curso
 * @property $categoria_curso_id
 * @property $created_at
 * @property $updated_at
 *
 * @property CategoriasCurso $categoriasCurso
 * @property Turma[] $turmas
 * @property Turma[] $turmas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Curso extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_curso', 'cor_curso', 'valor_curso', 'status_curso', 'categoria_curso_id'];

    protected $casts = [
        'status_curso' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoriasCurso()
    {
        return $this->belongsTo(\App\Models\CategoriasCurso::class, 'categoria_curso_id', 'id');
    }

    /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    public function turmas()
    {
        return $this->hasMany(\App\Models\Turma::class, 'id', 'curso_id');
    }
}
