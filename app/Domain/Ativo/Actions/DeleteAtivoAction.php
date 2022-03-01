<?php

namespace App\Domain\Ativo\Actions;

use App\Models\Ativo;
use App\Models\Operacao;

class DeleteAtivoAction
{
    public function __invoke($id)
    {
        $ativo = Ativo::with(['operacoes', 'rebalanceamentosAtivo'])->find($id);
        $operacoes = Operacao::where('ativo_id', $id);

        if (!$ativo) {
            throw new \Exception('Operação não encontrada: ' . $id);
        }

        if ($ativo->operacoes->count() > 0) {
            throw new \Exception('Não é possível excluir um ativo que possui operações.');
        }

        if ($ativo->rebalanceamentosAtivo->count() > 0) {
            throw new \Exception('Não é possível excluir um ativo que possui rebalanceamentos.');
        }

        $ativo->delete();
    }
}