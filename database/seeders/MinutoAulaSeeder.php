<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Importe o Carbon para lidar com timestamps
use Illuminate\Support\Facades\DB; // Importe o Facade DB para interagir com a base de dados

class MinutoAulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $minutos = [
            ['quant_minuto_aula' => 5],
            ['quant_minuto_aula' => 10],
            ['quant_minuto_aula' => 15],
            ['quant_minuto_aula' => 20],
            ['quant_minuto_aula' => 25],
            ['quant_minuto_aula' => 30],
            ['quant_minuto_aula' => 35],
            ['quant_minuto_aula' => 40],
            ['quant_minuto_aula' => 45],
            ['quant_minuto_aula' => 50],
            ['quant_minuto_aula' => 55],
            ['quant_minuto_aula' => 60],
            ['quant_minuto_aula' => 65],
            ['quant_minuto_aula' => 70],
            ['quant_minuto_aula' => 75],
            ['quant_minuto_aula' => 80],
            ['quant_minuto_aula' => 85],
            ['quant_minuto_aula' => 90],
            ['quant_minuto_aula' => 95],
            ['quant_minuto_aula' => 100],
            ['quant_minuto_aula' => 105],
            ['quant_minuto_aula' => 110],
            ['quant_minuto_aula' => 115],
            ['quant_minuto_aula' => 120],
        ];

        $agora = Carbon::now();
        $dadosParaInserir = array_map(function ($tipo) use ($agora) {
            $tipo['created_at'] = $agora;
            $tipo['updated_at'] = $agora;
            return $tipo;
        }, $minutos);

        // 3. Insere todos os registos na base de dados com uma única consulta.
        //    Isto é muito mais rápido e eficiente do que inserir um por um.
        DB::table('minutos_aulas')->insert($dadosParaInserir);
    }
}
