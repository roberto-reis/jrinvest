<?php

namespace App\Domain\Rebalanceamento\DTO;

use App\Domain\Rebalanceamento\Requests\RebalanceamentoAtivoRequest;

class RebalanceamentoAtivoDTO
{
    public int $ativo_id;
    public float $porcentagem;

    public function __construct(int $ativo_id, float $porcentagem)
    {
        $this->ativo_id = $ativo_id;
        $this->porcentagem = $porcentagem;
    }

    public static function fromRequest(RebalanceamentoAtivoRequest $request): RebalanceamentoAtivoDTO
    {
        return new self(
            $request->ativo_id,
            $request->porcentagem
        );
    }

}