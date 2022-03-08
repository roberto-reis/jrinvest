<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Models\RebalanceamentoClasse;
use App\Domain\Rebalanceamento\DTO\RebalanceamentoClasseDTO;

class UpdateRebalanceamentoClasseAction
{
    public function __invoke(RebalanceamentoClasseDTO $rebalanceamentoClasseDTO, $id = null): RebalanceamentoClasse
    {
        $rebalanceamentoClasse = RebalanceamentoClasse::find($id);

        if (is_null($rebalanceamentoClasse)) {
            throw new \Exception('Rebalanceamento classe de Ativo não encontrada!');
        }
        
        $rebalanceamentoClasse->classe_ativo_id = $rebalanceamentoClasseDTO->classe_ativo_id;
        $rebalanceamentoClasse->percentual = $rebalanceamentoClasseDTO->percentual;
        $rebalanceamentoClasse->save();

        return $rebalanceamentoClasse;
    }
}