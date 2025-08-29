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
        Schema::create('dias_nao_letivos', function (Blueprint $table) {
            $table->id();
            $table->date('data_dia_nao_letivo');
            $table->string('descricao_dia_nao_letivo', 220);
            $table->enum('tipo_feriado_dia_nao_letivo', ['Municipal', 'Estadual', 'Nacional', 'Emenda', 'Ponto Facultativo']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_nao_letivos');
    }
};
