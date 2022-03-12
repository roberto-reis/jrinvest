<?php

namespace App\Domain\Ativo\Actions;

use App\Domain\Ativo\DTO\AtivoDTO;
use App\Domain\Ativo\Models\Ativo;

class UpdateAtivoAction
{
    public function __invoke(AtivoDTO $ativoDTO, $id = null): Ativo
    {
        $ativo = Ativo::find($id);

        if (!$ativo) {
            throw new \Exception('Operação não encontrada!');
        }

        $ativo->codigo = $ativoDTO->codigo;
        $ativo->classe_ativo_id = $ativoDTO->classe_ativo;
        $ativo->nome = $ativoDTO->nome;
        $ativo->setor = $ativoDTO->setor;
        $ativo->save();
        
        return $ativo;
    }
}