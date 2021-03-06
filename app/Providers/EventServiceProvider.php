<?php

namespace App\Providers;

use App\Domain\Ativo\Models\Ativo;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Domain\Operacao\Models\Operacao;
use App\Domain\Ativo\Observers\AtivoObserver;
use App\Domain\Operacao\Observers\OperacaoObserver;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Operacao::observe(OperacaoObserver::class);
        Ativo::observe(AtivoObserver::class);
    }
}
