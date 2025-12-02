<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentaLog extends Model
{
    use HasFactory;

    // Define explicitamente o nome da tabela (para evitar erros de plural)
    protected $table = 'movimenta_log';

    // Permite que estes campos sejam gravados
    protected $fillable = [
        'colaborador_id',
        'turma_id',
        'data_movimentacao',
        'onde_estava',
        'onde_esta',
        // 'acao', // Adiciona este só se tiveres criado na migration
        // 'descricao' // Adiciona este só se tiveres criado na migration
    ];

    // Garante que o Laravel gerencia o created_at e updated_at
    public $timestamps = true;

    // (Opcional) Relacionamentos para facilitar a leitura depois
    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turma_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Colaboradore::class, 'user_id'); // Ajusta 'Colaborador' para o nome do teu model de usuário
    }
}
