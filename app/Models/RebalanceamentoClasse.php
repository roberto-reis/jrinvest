<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RebalanceamentoClasse extends Model
{
    use HasFactory;
    // use HasCompositePrimaryKeyTrait;

    protected $table = 'rebalanceamento_classes';
    protected $primaryKey = 'id';

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
