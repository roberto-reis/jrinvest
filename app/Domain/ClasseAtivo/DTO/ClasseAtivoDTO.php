<?php

namespace App\Domain\ClasseAtivo\DTO;

use App\Domain\ClasseAtivo\Requests\ClasseAtivoRequest;



class ClasseAtivoDTO
{
    public string $nome;
    public string $descricao;

    public function __construct(string $nome, string $descricao)
    {
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public static function fromRequest(ClasseAtivoRequest $request): ClasseAtivoDTO
    {
        return new self(
            $request->nome,
            $request->descricao,
        );
    }

}