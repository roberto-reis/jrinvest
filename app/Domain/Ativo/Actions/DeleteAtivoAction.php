<?php

namespace App\Domain\Ativo\Actions;

use App\Models\Ativo;
use App\Models\Operacao;
use Illuminate\Support\Facades\Log;

class DeleteAtivoAction
{
    public function __invoke($id)
    {
        $ativo = Ativo::find($id);
        $operacoes = Operacao::where('ativo_id', $id);

        if (!$ativo) {
            throw new \Exception('Operação não encontrada: ' . $id);
        }

        if ($operacoes->count() > 0) {
            throw new \Exception('Não é possível excluir um ativo que possui operações.');
        }

        $ativo->delete();
    }
}