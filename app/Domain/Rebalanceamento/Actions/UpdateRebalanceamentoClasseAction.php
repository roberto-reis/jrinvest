<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Exceptions\RebalanceamentoException;
use App\Domain\Rebalanceamento\DTO\RebalanceamentoClasseDTO;
use App\Domain\Rebalanceamento\Models\RebalanceamentoClasse;

class UpdateRebalanceamentoClasseAction
{
    public function __invoke(RebalanceamentoClasseDTO $rebalanceamentoClasseDTO, $id = null): RebalanceamentoClasse
    {
        $rebalanceamentoClasse = RebalanceamentoClasse::query()->find($id);

        if (is_null($rebalanceamentoClasse)) {
            throw new RebalanceamentoException('Rebalanceamento classe de Ativo não encontrada!');
        }

        $percentualTotal = RebalanceamentoClasse::query()->where('user_id', auth()->user()->id)->where('id', '<>', $id)->sum('percentual');
        $percentualSomaTotal = $percentualTotal + $rebalanceamentoClasseDTO->percentual;

        if ($percentualSomaTotal > 100) {
            throw new RebalanceamentoException('A soma total do meta/objetivo não pode ser maior que 100%');
        }
        
        $rebalanceamentoClasse->classe_ativo_id = $rebalanceamentoClasseDTO->classe_ativo_id;
        $rebalanceamentoClasse->percentual = $rebalanceamentoClasseDTO->percentual;
        $rebalanceamentoClasse->save();

        return $rebalanceamentoClasse;
    }
}