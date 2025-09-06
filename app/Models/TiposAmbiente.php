<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposAmbiente
 *
 * @property $id
 * @property $nome_tipo_ambiente
 * @property $created_at
 * @property $updated_at
 *
 * @property Ambiente[] $ambientes
 * @property Ambiente[] $ambientes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TiposAmbiente extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_tipo_ambiente'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ambientes()
    {
        return $this->hasMany(\App\Models\Ambiente::class, 'id', 'tipo_ambiente_id');
    }
}
