<?php

namespace App\Domain\Operacao\Actions;

use App\Models\Operacao;
use App\Domain\Operacao\DTO\OperacaoDTO;

class UpdateOperacaoAction
{
    public function __invoke(OperacaoDTO $operacaoDTO, $id = null): Operacao
    {
        $operacao = Operacao::find($id);

        if (!$operacao) {
            throw new \Exception('Operação não encontrada!');
        }

        $operacao->ativo_id = $operacaoDTO->ativo;
        $operacao->tipo_operacao = $operacaoDTO->tipo_operacao;
        $operacao->created_at = $operacaoDTO->data_operacao;
        $operacao->cotacao_preco = $operacaoDTO->cotacao;
        $operacao->quantidade = $operacaoDTO->quantidade;
        $operacao->corretora = $operacaoDTO->corretora;
        $operacao->save();
        
        return $operacao;
    }
}