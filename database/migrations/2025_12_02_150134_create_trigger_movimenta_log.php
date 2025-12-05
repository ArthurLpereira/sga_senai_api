<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Usaremos a sintaxe MySQL padrão para verificar a diferença,
        // garantindo que lide corretamente com valores NULL.
        DB::unprepared('
            CREATE TRIGGER trg_log_movimentacao_turma
            AFTER UPDATE ON turmas
            FOR EACH ROW
            BEGIN
                -- Verifica se a coluna ambiente_id mudou (usando sintaxe MySQL/MariaDB)
                -- Dispara se os valores forem diferentes, ou se um for NULL e o outro não.
                IF OLD.ambiente_id != NEW.ambiente_id OR (OLD.ambiente_id IS NULL AND NEW.ambiente_id IS NOT NULL) OR (OLD.ambiente_id IS NOT NULL AND NEW.ambiente_id IS NULL) THEN
                    INSERT INTO movimenta_log (
                        colaborador_id,
                        turma_id,
                        data_movimentacao,
                        onde_estava,
                        onde_esta,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        1,                  -- ID do Colaborador (Placeholder, ajuste!)
                        NEW.id,             -- ID da Turma
                        CURDATE(),          -- Data
                        OLD.ambiente_id,    -- ID do Ambiente Antigo
                        NEW.ambiente_id,    -- ID do Ambiente Novo
                        NOW(),
                        NOW()
                    );
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_log_movimentacao_turma');
    }
};
