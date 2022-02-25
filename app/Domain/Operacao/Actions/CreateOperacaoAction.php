<?php

namespace App\Domain\Operacao\Actions;

use App\Domain\Operacao\DTO\OperacaoDTO;
use App\Models\Operacao;
use Illuminate\Support\Facades\Auth;

class CreateOperacaoAction
{
    public function __invoke(OperacaoDTO $operacaoDTO): Operacao
    {
        $operacao = new Operacao();
        $operacao->user_id = Auth::user()->id;
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