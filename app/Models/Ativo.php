<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ativo extends Model
{
    use HasFactory;

    protected $table = 'ativos';

    protected $fillable = [
        'codigo',
        'classe_ativo_id',
        'derscricao',
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

    public function getNomeClasseAtivoAttribute()
    {
        return $this->classeAtivo()->first()->nome;
    }

}
