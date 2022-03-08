<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Domain\Rebalanceamento\DTO\RebalanceamentoAtivoDTO;
use App\Models\RebalanceamentoAtivo;

class CreateRebalanceamentoAtivoAction
{
    public function __invoke(RebalanceamentoAtivoDTO $rebalanceamentoAtivoDTO): RebalanceamentoAtivo
    {
        $rebalanceamentoAtivo = new RebalanceamentoAtivo();
        $rebalanceamentoAtivo->user_id = auth()->user()->id;
        $rebalanceamentoAtivo->ativo_id = $rebalanceamentoAtivoDTO->ativo_id;
        $rebalanceamentoAtivo->percentual = $rebalanceamentoAtivoDTO->percentual;
        $rebalanceamentoAtivo->save();

        return $rebalanceamentoAtivo;
    }
}