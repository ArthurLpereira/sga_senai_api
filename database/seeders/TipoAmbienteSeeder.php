<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Importe o Facade DB para interagir com a base de dados
use Carbon\Carbon; // Importe o Carbon para lidar com timestamps

class TipoAmbienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Este método será executado quando você chamar o seeder.
     */
    public function run(): void
    {
        // 1. (Opcional, mas recomendado) Limpa a tabela antes de a popular.
        // //    Isto evita duplicados se o seeder for executado mais de uma vez.
        // DB::table('tipos_ambientes')->truncate();

        // 2. Cria um array com todos os dados que queremos inserir.
        $tipos = [
            ['nome_tipo_ambiente' => 'Automobilística'],
            ['nome_tipo_ambiente' => 'Metalmecânica'],
            ['nome_tipo_ambiente' => 'Tecnologia da Informação'],
            ['nome_tipo_ambiente' => 'Química'],
            ['nome_tipo_ambiente' => 'Panificação'],
            ['nome_tipo_ambiente' => 'Eletroeletrônica'],
            ['nome_tipo_ambiente' => 'Oficina'],
            ['nome_tipo_ambiente' => 'Descentralizado'],
        ];

        // Adiciona os timestamps `created_at` e `updated_at` a cada registo.
        $agora = Carbon::now();
        $dadosParaInserir = array_map(function ($tipo) use ($agora) {
            $tipo['created_at'] = $agora;
            $tipo['updated_at'] = $agora;
            return $tipo;
        }, $tipos);

        // 3. Insere todos os registos na base de dados com uma única consulta.
        //    Isto é muito mais rápido e eficiente do que inserir um por um.
        DB::table('tipos_ambientes')->insert($dadosParaInserir);
    }
}
