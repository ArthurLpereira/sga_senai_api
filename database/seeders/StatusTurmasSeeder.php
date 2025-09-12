<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Importe o Carbon para lidar com timestamps
use Illuminate\Support\Facades\DB; // Importe o Facade DB para interagir com a base de dados

class StatusTurmasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [
            ['nome_status_turma' => 'Criada'],
            ['nome_status_turma' => 'Matrícula provisória'],
            ['nome_status_turma' => 'Iniciada'],
            ['nome_status_turma' => 'Concluída'],
            ['nome_status_turma' => 'Cancelada'],
        ];

        $agora = Carbon::now();
        $dadosParaInserir = array_map(function ($tipo) use ($agora) {
            $tipo['created_at'] = $agora;
            $tipo['updated_at'] = $agora;
            return $tipo;
        }, $status);

        // 3. Insere todos os registos na base de dados com uma única consulta.
        //    Isto é muito mais rápido e eficiente do que inserir um por um.
        DB::table('status_turmas')->insert($dadosParaInserir);
    }
}
