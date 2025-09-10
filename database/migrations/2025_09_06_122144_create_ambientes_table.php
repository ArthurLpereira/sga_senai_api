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
        Schema::create('ambientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_ambiente', 220);
            $table->integer('num_ambiente')->nullable();
            $table->integer('capacidade_ambiente');
            $table->enum('status_ambiente', [0, 1])->default(1)->nullable();
            $table->foreignId('tipo_ambiente_id')->constrained('tipos_ambientes', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambientes');
    }
};
