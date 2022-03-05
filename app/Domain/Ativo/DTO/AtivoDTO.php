<?php

namespace App\Domain\Ativo\DTO;

use App\Domain\Ativo\Requests\AtivoRequest;


class AtivoDTO
{
    public string $codigo;
    public string $nome;
    public string $setor;
    public string $classe_ativo;

    public function __construct(string $codigo, string $nome, string $setor, string $classe_ativo)
    {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->setor = $setor;
        $this->classe_ativo = $classe_ativo;
    }

    public static function fromRequest(AtivoRequest $request): AtivoDTO
    {
        return new self(
            $request->codigo,
            $request->nome,
            $request->setor,
            $request->classe_ativo
        );
    }

}