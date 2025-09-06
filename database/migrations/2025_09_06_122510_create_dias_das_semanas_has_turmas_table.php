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
        Schema::create('dias_das_semanas_has_turmas', function (Blueprint $table) {
            $table->foreignId('dia_da_semana_id')->constrained('dias_das_semanas', 'id');
            $table->foreignId('turma_id')->constrained('turmas', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_das_semanas_has_turmas');
    }
};
