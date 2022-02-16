<?php

namespace App\Domain\ClasseAtivo\Actions;

use App\Models\ClasseAtivo;
use App\Domain\ClasseAtivo\DTO\ClasseAtivoDTO;

class UpdateClasseAtivoAction
{
    public function __invoke(ClasseAtivoDTO $classeAtivoDTO, $id = null): ClasseAtivo
    {
        $classeAtivo = ClasseAtivo::find($id);

        if (!$classeAtivo) {
            throw new \Exception('Classe de Ativo não encontrada!');
        }

        $classeAtivo->nome = $classeAtivoDTO->nome;
        $classeAtivo->descricao = $classeAtivoDTO->descricao;
        $classeAtivo->save();
        
        return $classeAtivo;
    }
}