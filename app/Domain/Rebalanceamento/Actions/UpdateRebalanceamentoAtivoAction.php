<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Exceptions\RebalanceamentoException;
use App\Domain\Rebalanceamento\DTO\RebalanceamentoAtivoDTO;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;

class UpdateRebalanceamentoAtivoAction
{
    public function __invoke(RebalanceamentoAtivoDTO $rebalanceamentoAtivoDTO, $id = null): RebalanceamentoAtivo
    {
        $rebalanceamentoAtivo = RebalanceamentoAtivo::query()->find($id);

        if (is_null($rebalanceamentoAtivo)) {
            throw new RebalanceamentoException('Rebalanceamento classe de Ativo não encontrada!');
        }

        $percentualTotal = RebalanceamentoAtivo::query()->where('user_id', auth()->user()->id)->where('id', '<>', $id)->sum('percentual');
        $percentualSomaTotal = $percentualTotal + $rebalanceamentoAtivoDTO->percentual; 
        if ($percentualSomaTotal > 100) {
            throw new RebalanceamentoException('A soma total do meta/objetivo não pode ser maior que 100%');
        }
        
        $rebalanceamentoAtivo->ativo_id = $rebalanceamentoAtivoDTO->ativo_id;
        $rebalanceamentoAtivo->percentual = $rebalanceamentoAtivoDTO->percentual;
        $rebalanceamentoAtivo->save();

        return $rebalanceamentoAtivo;
    }
}