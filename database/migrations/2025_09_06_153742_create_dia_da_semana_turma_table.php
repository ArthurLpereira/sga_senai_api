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
        Schema::create('dia_da_semana_turma', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dia_da_semana_id')->constrained('dias_das_semanas', 'id');
            $table->foreignId('turma_id')->constrained('turmas', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dia_da_semana_turma');
    }
};
