<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RebalanceamentoAtivo extends Model
{
    use HasFactory;

    protected $table = 'rebalanceamento_ativos';

    protected $fillable = [
        'user_id',
        'ativo_id',
        'porcentagem',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ativo()
    {
        return $this->belongsTo(Ativo::class, 'ativo_id', 'id');
    }
    
}
