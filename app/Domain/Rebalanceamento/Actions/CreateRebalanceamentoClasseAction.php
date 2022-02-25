<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Domain\Rebalanceamento\DTO\RebalanceamentoClasseDTO;
use App\Models\RebalanceamentoClasse;

class CreateRebalanceamentoClasseAction
{
    public function __invoke(RebalanceamentoClasseDTO $rebalanceamentoClasseDTO): RebalanceamentoClasse
    {
        $rebalanceamentoClasse = new RebalanceamentoClasse();
        $rebalanceamentoClasse->user_id = auth()->user()->id;
        $rebalanceamentoClasse->classe_ativo_id = $rebalanceamentoClasseDTO->classe_ativo_id;
        $rebalanceamentoClasse->porcentagem = $rebalanceamentoClasseDTO->porcentagem;
        $rebalanceamentoClasse->save();

        return $rebalanceamentoClasse;
    }
}