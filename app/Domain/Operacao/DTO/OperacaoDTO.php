<?php
namespace App\Domain\Operacao\DTO;

use App\Domain\Operacao\Requests\OperacaoRequest;

class OperacaoDTO
{
    public string $ativo;
    public string $tipo_operacao;
    public string $corretora;
    public string $data_operacao;
    public string $cotacao;
    public string $quantidade;

    public function __construct($ativo, $tipo_operacao, $corretora, $data_operacao, $cotacao, $quantidade)
    {
        $this->ativo = $ativo;
        $this->tipo_operacao = $tipo_operacao;
        $this->corretora = $corretora;
        $this->data_operacao = $data_operacao;
        $this->cotacao = $cotacao;
        $this->quantidade = $quantidade;
    }

    public static function fromRequest(OperacaoRequest $request): OperacaoDTO
    {
        return new Self(
            $request->ativo,
            $request->tipo_operacao,
            $request->corretora,
            $request->data_operacao,
            $request->cotacao,
            $request->quantidade
        );
    }

}