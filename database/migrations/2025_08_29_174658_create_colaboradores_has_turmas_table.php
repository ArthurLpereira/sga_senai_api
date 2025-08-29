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
        Schema::create('colaboradores_has_turmas', function (Blueprint $table) {
            $table->foreignId('colaborador_id')->constrained('colaboradores', 'id');
            $table->foreignId('turma_id')->constrained('turmas', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaboradores_has_turmas');
    }
};
