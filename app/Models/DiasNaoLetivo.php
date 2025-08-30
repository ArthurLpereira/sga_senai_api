<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DiasNaoLetivo
 *
 * @property $id
 * @property $data_dia_nao_letivo
 * @property $descricao_dia_nao_letivo
 * @property $tipo_feriado_dia_nao_letivo
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DiasNaoLetivo extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['data_dia_nao_letivo', 'descricao_dia_nao_letivo', 'tipo_feriado_dia_nao_letivo'];


}
