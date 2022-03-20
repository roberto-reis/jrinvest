<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Exceptions\RebalanceamentoException;
use App\Domain\Rebalanceamento\DTO\RebalanceamentoAtivoDTO;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;

class CreateRebalanceamentoAtivoAction
{
    public function __invoke(RebalanceamentoAtivoDTO $rebalanceamentoAtivoDTO): RebalanceamentoAtivo
    {

        $percentualTotal = RebalanceamentoAtivo::query()->where('user_id', auth()->user()->id)->sum('percentual');
        $percentualSomaTotal = $percentualTotal + $rebalanceamentoAtivoDTO->percentual;

        if ($percentualSomaTotal > 100) {
            throw new RebalanceamentoException('A soma total do meta/objetivo não pode ser maior que 100%');
        }

        $rebalanceamentoAtivo = new RebalanceamentoAtivo();
        $rebalanceamentoAtivo->user_id = auth()->user()->id;
        $rebalanceamentoAtivo->ativo_id = $rebalanceamentoAtivoDTO->ativo_id;
        $rebalanceamentoAtivo->percentual = $rebalanceamentoAtivoDTO->percentual;
        $rebalanceamentoAtivo->save();

        return $rebalanceamentoAtivo;
    }
}