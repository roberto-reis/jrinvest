<?php

namespace App\Domain\Rebalanceamento\Models;

use App\Domain\User\Models\User;
use App\Models\Traits\UuidTrait;
use App\Domain\Ativo\Models\Ativo;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\RebalanceamentoAtivoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RebalanceamentoAtivo extends Model
{
    use UuidTrait;
    use HasFactory;

    protected $table = 'rebalanceamento_ativos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    protected $fillable = [
        'user_id',
        'ativo_id',
        'percentual',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ativo()
    {
        return $this->belongsTo(Ativo::class, 'ativo_id', 'id');
    }

    protected static function newFactory()
    {
        return new RebalanceamentoAtivoFactory();
    }
    
}
