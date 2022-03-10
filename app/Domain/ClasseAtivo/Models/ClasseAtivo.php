<?php

namespace App\Domain\ClasseAtivo\Models;

use App\Models\Traits\UuidTrait;
use App\Domain\Ativo\Models\Ativo;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ClasseAtivoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClasseAtivo extends Model
{
    use UuidTrait;
    use HasFactory;

    protected $table = 'classes_ativos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function ativos()
    {
        return $this->hasMany(Ativo::class, 'classe_ativo_id', 'id');
    }

    protected static function newFactory()
    {
        return new ClasseAtivoFactory();
    }

}
