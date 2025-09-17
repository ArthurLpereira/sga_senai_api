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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_curso', 220);
            $table->string('cor_curso', 45);
            $table->decimal('valor_curso', 10, 2)->nullable();
            $table->foreignId('categoria_curso_id')->constrained('categorias_cursos', 'id');
            $table->enum('status_curso', [0, 1])->default(1);
            $table->integer('carga_horaria_curso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
