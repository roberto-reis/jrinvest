<?php

namespace App\Domain\Carteira\Models;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentabilidadeCarteira extends Model
{
    use UuidTrait;
    use HasFactory;

    protected $table = 'rentabilidades_carteiras';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    protected $fillable = [
        'user_id',
        'custo_total_carteira',
        'valor_total_carteira',
        'rentabilidade_valor',
        'rentabilidade_percentual',
        'payload_ativos',
    ];
    
}
