<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Exceptions\RebalanceamentoException;
use App\Domain\Rebalanceamento\DTO\RebalanceamentoClasseDTO;
use App\Domain\Rebalanceamento\Models\RebalanceamentoClasse;

class CreateRebalanceamentoClasseAction
{
    public function __invoke(RebalanceamentoClasseDTO $rebalanceamentoClasseDTO): RebalanceamentoClasse
    {

        $percentualTotal = RebalanceamentoClasse::query()->where('user_id', auth()->user()->id)->sum('percentual');
        $percentualSomaTotal = $percentualTotal + $rebalanceamentoClasseDTO->percentual;

        if ($percentualSomaTotal > 100) {
            throw new RebalanceamentoException('A soma total do meta/objetivo não pode ser maior que 100%');
        }

        $rebalanceamentoClasse = new RebalanceamentoClasse();
        $rebalanceamentoClasse->user_id = auth()->user()->id;
        $rebalanceamentoClasse->classe_ativo_id = $rebalanceamentoClasseDTO->classe_ativo_id;
        $rebalanceamentoClasse->percentual = $rebalanceamentoClasseDTO->percentual;
        $rebalanceamentoClasse->save();

        return $rebalanceamentoClasse;
    }
}