<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Importe o Carbon para lidar com timestamps
use Illuminate\Support\Facades\DB; // Importe o Facade DB para interagir com a base de dados

class CategoriaCursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nome_categoria_curso' => 'TEC'],
            ['nome_categoria_curso' => 'CAI'],
            ['nome_categoria_curso' => 'FIC A'],
            ['nome_categoria_curso' => 'FIC E'],
            ['nome_categoria_curso' => 'FIC I'],
        ];

        $agora = Carbon::now();
        $dadosParaInserir = array_map(function ($tipo) use ($agora) {
            $tipo['created_at'] = $agora;
            $tipo['updated_at'] = $agora;
            return $tipo;
        }, $categorias);

        // 3. Insere todos os registos na base de dados com uma única consulta.
        //    Isto é muito mais rápido e eficiente do que inserir um por um.
        DB::table('categorias_cursos')->insert($dadosParaInserir);
    }
}
