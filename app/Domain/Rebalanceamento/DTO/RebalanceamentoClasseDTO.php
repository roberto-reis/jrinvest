<?php

namespace App\Domain\Rebalanceamento\DTO;

use App\Domain\Rebalanceamento\Requests\RebalanceamentoClasseRequest;

class RebalanceamentoClasseDTO
{
    public string $classe_ativo_id;
    public float $percentual;

    public function __construct(string $classe_ativo_id, float $percentual)
    {
        $this->classe_ativo_id = $classe_ativo_id;
        $this->percentual = $percentual;
    }

    public static function fromRequest(RebalanceamentoClasseRequest $request): RebalanceamentoClasseDTO
    {
        return new self(
            $request->classe_ativo_id,
            $request->percentual
        );
    }

}