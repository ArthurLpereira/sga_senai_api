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
        Schema::create('alteracao_turma', function (Blueprint $table) {
            $table->id('id_alteracao_turma');
            $table->dateTime('data_hora_alteracao_turma');
            $table->string('descricao_alteracao_turma', 220);
            $table->string('justificativa_alteracao_turma', 220);
            $table->foreignId('colaborador_id')->constrained('colaboradores', 'id_colaborador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alteracao_turma');
    }
};
