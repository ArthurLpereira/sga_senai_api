<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Importe o Carbon para lidar com timestamps
use Illuminate\Support\Facades\DB; // Importe o Facade DB para interagir com a base de dados

class DiaDaSemanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dias = [
            ['nome_dia_da_semana' => 'Domingo'],
            ['nome_dia_da_semana' => 'Segunda-feira'],
            ['nome_dia_da_semana' => 'Terça-feira'],
            ['nome_dia_da_semana' => 'Quarta-feira'],
            ['nome_dia_da_semana' => 'Quinta-feira'],
            ['nome_dia_da_semana' => 'Sexta-feira'],
            ['nome_dia_da_semana' => 'Sábado'],
        ];

        $agora = Carbon::now();
        $dadosParaInserir = array_map(function ($tipo) use ($agora) {
            $tipo['created_at'] = $agora;
            $tipo['updated_at'] = $agora;
            return $tipo;
        }, $dias);

        // 3. Insere todos os registos na base de dados com uma única consulta.
        //    Isto é muito mais rápido e eficiente do que inserir um por um.
        DB::table('dias_das_semanas')->insert($dadosParaInserir);
    }
}
