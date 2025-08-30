<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposColaboradore
 *
 * @property $id
 * @property $nome_tipo_colaborador
 * @property $created_at
 * @property $updated_at
 *
 * @property Colaboladore[] $colaboladores
 * @property Colaboradore[] $colaboradores
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TiposColaboradore extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_tipo_colaborador'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    public function colaboradores()
    {
        return $this->hasMany(\App\Models\Colaboradore::class, 'id', 'tipo_colaborador_id');
    }
}
