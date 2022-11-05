<?php

namespace App\Domain\Ativo\Observers;

use App\Domain\Ativo\Models\Ativo;
use Illuminate\Support\Facades\Artisan;

class AtivoObserver
{
    /**
     * Handle the Ativo "created" event.
     *
     * @param App\Domain\Ativo\Models\Ativo  $ativo
     * @return void
     */
    public function created(Ativo $ativo)
    {
        Artisan::call('sync:cotacao');
    }

    /**
     * Handle the Ativo "updated" event.
     *
     * @param App\Domain\Ativo\Models\Ativo   $ativo
     * @return void
     */
    public function updated(Ativo $ativo)
    {
        Artisan::call('sync:cotacao');
    }


}
