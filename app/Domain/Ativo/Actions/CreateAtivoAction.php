<?php

namespace App\Domain\Ativo\Actions;

use App\Domain\Ativo\DTO\AtivoDTO;
use App\Models\Ativo;

class CreateAtivoAction
{
    public function __invoke(AtivoDTO $ativoDTO): Ativo
    {
        $ativo = new Ativo();
        $ativo->codigo = $ativoDTO->codigo;
        $ativo->classe_ativo_id = $ativoDTO->classe_ativo;
        $ativo->descricao = $ativoDTO->descricao;
        $ativo->setor = $ativoDTO->setor;
        $ativo->save();

        return $ativo;
    }
}