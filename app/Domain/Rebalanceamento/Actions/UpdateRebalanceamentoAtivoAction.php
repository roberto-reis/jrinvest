<?php

namespace App\Domain\Rebalanceamento\Actions;

use App\Domain\Rebalanceamento\DTO\RebalanceamentoAtivoDTO;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;

class UpdateRebalanceamentoAtivoAction
{
    public function __invoke(RebalanceamentoAtivoDTO $rebalanceamentoAtivoDTO, $id = null): RebalanceamentoAtivo
    {
        $rebalanceamentoAtivo = RebalanceamentoAtivo::find($id);

        if (is_null($rebalanceamentoAtivo)) {
            throw new \Exception('Rebalanceamento por Ativo não encontrada!');
        }
        
        $rebalanceamentoAtivo->ativo_id = $rebalanceamentoAtivoDTO->ativo_id;
        $rebalanceamentoAtivo->percentual = $rebalanceamentoAtivoDTO->percentual;
        $rebalanceamentoAtivo->save();

        return $rebalanceamentoAtivo;
    }
}