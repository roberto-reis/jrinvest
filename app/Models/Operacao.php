<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Operacao extends Model
{
    use HasFactory;

    protected $table = 'operacoes';

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

    public function setquantidadeAttribute($value)
    {
        $value = str_replace([',', '.'], '', $value);

        $this->attributes['quantidade'] =  $value;
    }

    public function getQuantidadeAttribute($value)
    {
        return number_format($value, 2,',', '.');
    }

}
