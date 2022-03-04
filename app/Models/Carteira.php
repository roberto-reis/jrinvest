<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
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
        'valor_total',
    ];

    public function getValorTotalAttribute()
    {
        return $this->cotacao_preco * $this->attributes['quantidade_saldo'];
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
