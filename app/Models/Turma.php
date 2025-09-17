<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Turma
 *
 * @property $id
 * @property $nome_turma
 * @property $capacidade_turma
 * @property $data_inicio_turma
 * @property $data_termino_turma
 * @property $curso_id
 * @property $ambiente_id
 * @property $status_turma_id
 * @property $minuto_aula_id
 * @property $turno_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Ambiente $ambiente
 * @property Curso $curso
 * @property MinutosAula $minutosAula
 * @property StatusTurma $statusTurma
 * @property Turno $turno
 * @property ColaboladoresHasTurma[] $colaboladoresHasTurmas
 * @property DiasDaSemanasHasTurma[] $diasDaSemanasHasTurmas
 * @property ColaboradoresHasTurma[] $colaboradoresHasTurmas
 * @property DiasDasSemanasHasTurma[] $diasDasSemanasHasTurmas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Turma extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nome_turma', 'capacidade_turma', 'data_inicio_turma', 'data_termino_turma', 'curso_id', 'ambiente_id', 'status_turma_id', 'minuto_aula_id', 'turno_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ambiente()
    {
        return $this->belongsTo(\App\Models\Ambiente::class, 'ambiente_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function curso()
    {
        return $this->belongsTo(\App\Models\Curso::class, 'curso_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function minutosAula()
    {
        return $this->belongsTo(\App\Models\MinutosAula::class, 'minuto_aula_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusTurma()
    {
        return $this->belongsTo(\App\Models\StatusTurma::class, 'status_turma_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function turno()
    {
        return $this->belongsTo(\App\Models\Turno::class, 'turno_id', 'id');
    }

    public function colaboradores(): BelongsToMany
    {
        return $this->belongsToMany(
            Colaboradore::class,
            'colaborador_turma', // Nome da tabela pivot
            'turma_id',
            'colaborador_id'
        );
    }

    /**
     * Define o relacionamento Muitos-para-Muitos com Dias da Semana.
     */
    public function diasDaSemana(): BelongsToMany
    {
        return $this->belongsToMany(
            DiasDasSemana::class,
            'dia_da_semana_turma',
            'turma_id',
            'dia_da_semana_id'
        );
    }
}
