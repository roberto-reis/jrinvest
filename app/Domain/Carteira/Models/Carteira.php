<?php

namespace App\Domain\Carteira\Models;

use App\Domain\User\Models\User;
use App\Models\Traits\UuidTrait;
use App\Domain\Ativo\Models\Ativo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carteira extends Model
{
    use UuidTrait;
    use HasFactory;

    protected $table = 'carteiras';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    protected $fillable = [
        'user_id',
        'ativo_id',
        'quantidade_saldo',
        'preco_medio',
    ];

    protected $appends = [
        'custo_total_ativo',
    ];

    public function getCustoTotalAtivoAttribute()
    {
        return $this->quantidade_saldo * $this->preco_medio;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ativo()
    {
        return $this->belongsTo(Ativo::class, 'ativo_id', 'id');
    }
}
