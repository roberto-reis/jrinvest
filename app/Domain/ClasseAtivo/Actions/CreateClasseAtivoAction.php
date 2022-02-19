<?php

namespace App\Domain\ClasseAtivo\Actions;

use App\Models\ClasseAtivo;
use App\Domain\ClasseAtivo\DTO\ClasseAtivoDTO;

class CreateClasseAtivoAction
{
    public function __invoke(ClasseAtivoDTO $classeAtivoDTO): ClasseAtivo
    {
        $classeAtivo = new ClasseAtivo();
        $classeAtivo->nome = $classeAtivoDTO->nome;
        $classeAtivo->descricao = $classeAtivoDTO->descricao;
        $classeAtivo->save();

        return $classeAtivo;
    }
}