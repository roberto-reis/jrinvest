<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;

class DeleteRebalanceamentoAtivoAction
{
    public function __invoke($id)
    {
        $rebalanceamentoAtivo = RebalanceamentoAtivo::find($id);

        if (is_null($rebalanceamentoAtivo)) {
            throw new \Exception('Rebalanceamento por ativo não encontrada.');
        }

        $rebalanceamentoAtivo->delete();
    }
}