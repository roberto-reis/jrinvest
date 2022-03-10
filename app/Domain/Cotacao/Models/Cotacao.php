<?php

namespace App\Domain\Cotacao\Models;

use App\Models\Traits\UuidTrait;
use Database\Factories\CotacaoFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cotacao extends Model
{
    use UuidTrait;
    use HasFactory;

    protected $table = 'cotacoes';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    protected $fillable = [
        'ativo_id',
        'moeda_ref',
        'preco',
    ];

    public function getMoedaRefAttribute($value)
    {
        return strtoupper($value);
    }

    protected static function newFactory()
    {
        return new CotacaoFactory();
    }

}
