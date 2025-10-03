<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Importe o Carbon para lidar com timestamps
use Illuminate\Support\Facades\DB; // Importe o Facade DB para interagir com a base de dados

class TurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $turnos = [
            ['nome_turno' => 'Manhã'],
            ['nome_turno' => 'Tarde'],
            ['nome_turno' => 'Noite'],
            ['nome_turno' => 'Manhã-Tarde'],
            ['nome_turno' => 'Manhã-Noite'],
            ['nome_turno' => 'Tarde-Noite'],
            ['nome_turno' => 'Integral'],
        ];

        $agora = Carbon::now();
        $dadosParaInserir = array_map(function ($tipo) use ($agora) {
            $tipo['created_at'] = $agora;
            $tipo['updated_at'] = $agora;
            return $tipo;
        }, $turnos);

        // 3. Insere todos os registos na base de dados com uma única consulta.
        //    Isto é muito mais rápido e eficiente do que inserir um por um.
        DB::table('turnos')->insert($dadosParaInserir);
    }
}
