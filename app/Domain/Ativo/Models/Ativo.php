<?php

namespace App\Domain\Ativo\Models;

use App\Models\Traits\UuidTrait;
use Database\Factories\AtivoFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ativo extends Model
{
    use UuidTrait;
    use HasFactory;

    protected $table = 'ativos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    protected $fillable = [
        'codigo',
        'classe_ativo_id',
        'nome',
        'setor',
    ];

    protected $appends = [
        'nome_classe_ativo',
    ];

    public function operacoes()
    {
        return $this->hasMany(Operacao::class, 'ativo_id' , 'id');
    }

    public function classeAtivo()
    {
        return $this->belongsTo(ClasseAtivo::class, 'classe_ativo_id', 'id');
    }

    public function rebalanceamentosAtivo()
    {
        return $this->hasMany(RebalanceamentoAtivo::class, 'ativo_id', 'id');
    }

    public function getNomeClasseAtivoAttribute()
    {
        return $this->classeAtivo()->first()->nome;
    }

    protected static function newFactory()
    {
        return new AtivoFactory();
    }

}
