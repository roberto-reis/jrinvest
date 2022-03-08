<?php

namespace App\Domain\Rebalanceamento\DTO;

use App\Domain\Rebalanceamento\Requests\RebalanceamentoAtivoRequest;

class RebalanceamentoAtivoDTO
{
    public string $ativo_id;
    public float $percentual;

    public function __construct(string $ativo_id, float $percentual)
    {
        $this->ativo_id = $ativo_id;
        $this->percentual = $percentual;
    }

    public static function fromRequest(RebalanceamentoAtivoRequest $request): RebalanceamentoAtivoDTO
    {
        return new self(
            $request->ativo_id,
            $request->percentual
        );
    }

}