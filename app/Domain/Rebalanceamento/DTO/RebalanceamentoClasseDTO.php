<?php

namespace App\Domain\Rebalanceamento\DTO;

use App\Domain\Rebalanceamento\Requests\RebalanceamentoClasseRequest;

class RebalanceamentoClasseDTO
{
    public int $classe_ativo_id;
    public float $porcentagem;

    public function __construct(int $classe_ativo_id, float $porcentagem)
    {
        $this->classe_ativo_id = $classe_ativo_id;
        $this->porcentagem = $porcentagem;
    }

    public static function fromRequest(RebalanceamentoClasseRequest $request): RebalanceamentoClasseDTO
    {
        return new self(
            $request->classe_ativo_id,
            $request->porcentagem
        );
    }

}