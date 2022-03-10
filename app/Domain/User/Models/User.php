<?php

namespace App\Domain\User\Models;

use App\Models\Traits\UuidTrait;
use Laravel\Sanctum\HasApiTokens;
use Database\Factories\UserFactory;
use App\Domain\Operacao\Models\Operacao;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;
use App\Domain\Rebalanceamento\Models\RebalanceamentoClasse;

class User extends Authenticatable
{
    use UuidTrait;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $icrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'phone',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function operacoes()
    {
        return $this->hasMany(Operacao::class, 'user_id', 'id');
    }

    public function rebalanceamentoClasses()
    {
        return $this->hasMany(RebalanceamentoClasse::class, 'user_id', 'id');
    }

    public function rebalanceamentoAtivos()
    {
        return $this->hasMany(RebalanceamentoAtivo::class, 'user_id', 'id');
    }

    protected static function newFactory()
    {
        return new UserFactory();
    }
}
