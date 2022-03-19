<?php

namespace App\Domain\Ativo\Observers;

use App\Domain\Ativo\Models\Ativo;
use App\Domain\Cotacao\Jobs\CotacaoJob;

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
        CotacaoJob::dispatch();
    }

    /**
     * Handle the Ativo "updated" event.
     *
     * @param App\Domain\Ativo\Models\Ativo   $ativo
     * @return void
     */
    public function updated(Ativo $ativo)
    {
        CotacaoJob::dispatch();
    }


}
