<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoriasCurso
 *
 * @property $id
 * @property $nome_categoria_curso
 * @property $created_at
 * @property $updated_at
 *
 * @property Curso[] $cursos
 * @property Curso[] $cursos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CategoriasCurso extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_categoria_curso'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cursos()
    {
        return $this->hasMany(\App\Models\Curso::class, 'id', 'categoria_curso_id');
    }
}
