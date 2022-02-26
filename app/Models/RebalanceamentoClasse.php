<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
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
        'porcentagem',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function classeAtivo()
    {
        return $this->belongsTo(ClasseAtivo::class, 'classe_ativo_id');
    }

}
