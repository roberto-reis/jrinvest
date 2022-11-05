<?php

namespace App\Domain\Operacao\Actions;

use Illuminate\Support\Facades\Auth;
use App\Domain\Operacao\DTO\OperacaoDTO;
use App\Domain\Operacao\Models\Operacao;

class CreateOperacaoAction
{
    public function __invoke(OperacaoDTO $operacaoDTO): Operacao
    {
        $operacao = new Operacao();
        $operacao->user_id = Auth::user()->id;
        $operacao->ativo_id = $operacaoDTO->ativo;
        $operacao->tipo_operacao = $operacaoDTO->tipo_operacao;
        $operacao->data_operacao = $operacaoDTO->data_operacao;
        $operacao->cotacao_preco = numberFormatterToSave($operacaoDTO->cotacao);
        $operacao->quantidade = numberFormatterToSave($operacaoDTO->quantidade);
        $operacao->corretora = $operacaoDTO->corretora;
        $operacao->save();

        return $operacao;
    }
}