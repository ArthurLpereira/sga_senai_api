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
        Schema::create('colaboradores', function (Blueprint $table) {
            $table->id();
            $table->string('nome_colaborador', 220);
            $table->string('email_colaborador', 220);
            $table->string('especialidade_colaborador', 220);
            $table->enum('status_colaborador', [0, 1])->default(1);
            $table->foreignId('tipo_colaborador_id')->constrained('tipos_colaboradores', 'id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaboradores');
    }
};
