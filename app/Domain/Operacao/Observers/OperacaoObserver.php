<?php

namespace App\Domain\Operacao\Observers;

use App\Domain\Carteira\Jobs\ConsolidaCarteiraUserJob;
use App\Models\Operacao;

class OperacaoObserver
{
    /**
     * Handle the Operacao "created" event.
     *
     * @param  \App\Models\Operacao  $operacao
     * @return void
     */
    public function created()
    {
        ConsolidaCarteiraUserJob::dispatch();
    }

    /**
     * Handle the Operacao "updated" event.
     *
     * @param  \App\Models\Operacao  $operacao
     * @return void
     */
    public function updated()
    {
        ConsolidaCarteiraUserJob::dispatch();
    }

    /**
     * Handle the Operacao "deleted" event.
     *
     * @param  \App\Models\Operacao  $operacao
     * @return void
     */
    public function deleted()
    {
        ConsolidaCarteiraUserJob::dispatch();
    }

}
