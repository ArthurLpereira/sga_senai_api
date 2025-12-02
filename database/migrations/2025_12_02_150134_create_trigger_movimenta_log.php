<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cria a Trigger
        DB::unprepared('
            CREATE TRIGGER trg_log_movimentacao_turma
            AFTER UPDATE ON turmas
            FOR EACH ROW
            BEGIN
                IF OLD.ambiente_id <> NEW.ambiente_id THEN
                    INSERT INTO movimenta_log (turma_id, ambiente_anterior_id, ambiente_novo_id, user_id, created_at, updated_at)
                    VALUES (NEW.id, OLD.ambiente_id, NEW.ambiente_id, NULL, NOW(), NOW());
                END IF;
            END
        ');
    }

    public function down(): void
    {
        // Apaga a Trigger se reverteres a migration
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_movimentacao_turma');
    }
};
