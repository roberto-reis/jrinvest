<?php

namespace App\Domain\Operacao\Models;

use App\Models\Traits\UuidTrait;
use App\Domain\Ativo\Models\Ativo;
use Database\Factories\OperacaoFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Operacao extends Model
{
    use UuidTrait;
    use HasFactory;

    protected $table = 'operacoes';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    protected $fillable = [
        'user_id',
        'ativo_id',
        'tipo_operacao',
        'cotacao_preco',
        'quantidade',
        'corretora',
    ];

    protected $appends = [
        'valor_total',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ativo()
    {
        return $this->belongsTo(Ativo::class, 'ativo_id', 'id');
    }

    public function getValorTotalAttribute()
    {
        return $this->cotacao_preco * $this->attributes['quantidade'];
    }

    public function setCotacaoPrecoAttribute($value)
    {
        $this->attributes['cotacao_preco'] =  numberFormatterToSave($value);
    }

    public function setQuantidadeAttribute($value)
    {     
        $this->attributes['quantidade'] =  numberFormatterToSave($value);
    }

    protected static function newFactory()
    {
        return new OperacaoFactory();
    }

}
