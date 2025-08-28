<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->id('id_turma');
            $table->string('nome_turma', 220);
            $table->integer('capacidade_turma');
            $table->date('data_inicio_turma');
            $table->date('data_termino_turma');
            $table->foreignId('curso_id')->constrained('cursos', 'id_curso');
            $table->foreignId('ambiente_id')->constrained('ambientes', 'id_ambiente');
            $table->foreignId('status_turma_id')->constrained('status_turmas', 'id_status_turma');
            $table->foreignId('minuto_aula_id')->constrained('minutos_aulas', 'id_minuto_aula');
            $table->foreignId('turno_id')->constrained('turnos', 'id_turno');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};
