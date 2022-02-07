<?php

namespace App\Domain\Operacao\Actions;

use App\Models\Operacao;

class DeleteOperacaoAction
{
    public function __invoke($id): Operacao
    {
        $operacao = Operacao::find($id);
        if (!$operacao) {
            throw new \Exception('Operação não encontrada');
        }
        $operacao->delete();

        return $operacao;
    }
}