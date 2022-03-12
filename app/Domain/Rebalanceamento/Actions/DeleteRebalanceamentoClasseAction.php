<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Domain\Rebalanceamento\Models\RebalanceamentoClasse;

class DeleteRebalanceamentoClasseAction
{
    public function __invoke($id)
    {
        $rebalanceamentoClasse = RebalanceamentoClasse::find($id);

        if (is_null($rebalanceamentoClasse)) {
            throw new \Exception('Rebalanceamento de classe de ativo não encontrada.');
        }

        $rebalanceamentoClasse->delete();
    }
}