<?php

namespace App\Domain\ClasseAtivo\Actions;

use App\Models\Ativo;
use App\Models\ClasseAtivo;

class DeleteClasseAtivoAction
{
    public function __invoke($id)
    {
        $classeAtivo = ClasseAtivo::find($id);
        $ativo = Ativo::where('classe_ativo_id', $id);

        if (!$classeAtivo) {
            throw new \Exception('Operação não encontrada: ' . $id);
        }

        if ($ativo->count() > 0) {
            throw new \Exception('Não é possível excluir uma Classe de Ativo que possui Ativo cadastrado.');
        }

        $classeAtivo->delete();
    }
}