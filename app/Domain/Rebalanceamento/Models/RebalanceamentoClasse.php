<?php

namespace App\Domain\Rebalanceamento\Models;

use App\Domain\User\Models\User;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use App\Domain\ClasseAtivo\Models\ClasseAtivo;
use Database\Factories\RebalanceamentoClasseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RebalanceamentoClasse extends Model
{
    use UuidTrait;
    use HasFactory;

    protected $table = 'rebalanceamento_classes';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    protected $fillable = [
        'user_id',
        'classe_ativo_id',
        'percentual',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function classeAtivo()
    {
        return $this->belongsTo(ClasseAtivo::class, 'classe_ativo_id');
    }

    protected static function newFactory()
    {
        return new RebalanceamentoClasseFactory();
    }

}
