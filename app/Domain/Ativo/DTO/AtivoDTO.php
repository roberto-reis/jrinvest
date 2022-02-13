<?php

namespace App\Domain\Ativo\DTO;

use App\Domain\Ativo\Requests\AtivoRequest;


class AtivoDTO
{
    public string $codigo;
    public string $descricao;
    public string $setor;
    public int $classe_ativo;

    public function __construct(string $codigo, string $descricao, string $setor, int $classe_ativo)
    {
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->setor = $setor;
        $this->classe_ativo = $classe_ativo;
    }

    public static function fromRequest(AtivoRequest $request): AtivoDTO
    {
        return new self(
            $request->codigo,
            $request->descricao,
            $request->setor,
            $request->classe_ativo
        );
    }

}