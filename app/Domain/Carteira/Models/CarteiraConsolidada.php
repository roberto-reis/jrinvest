<?php

namespace App\Domain\Carteira\Models;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarteiraConsolidada extends Model
{
    use UuidTrait;
    use HasFactory;

    protected $table = 'corteiras_consolidadas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    protected $fillable = [
        'user_id',
        'ativo_id',
        'quantidade_saldo',
        'preco_medio',
        'custo_total_ativo',
        'cotacao',
        'valor_total_ativo',
        'percentual',
        'rentabilidade_valor',
        'rentabilidade_percentual',
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
