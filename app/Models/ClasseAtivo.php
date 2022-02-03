<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClasseAtivo extends Model
{
    use HasFactory;

    protected $table = 'classes_ativos';

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function ativos()
    {
        return $this->hasMany(Ativo::class, 'classe_ativo_id', 'id');
    }
}
