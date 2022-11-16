<?php

namespace App\Domain\Carteira\DTO;

use Illuminate\Support\Arr;

class CarteiraDTO
{
    public function __construct(
        public ?string $id,
        public ?string $user_id,
        public ?string $ativo_id,
        public ?float $quantidade_saldo,
        public ?float $preco_medio,
        public ?string $codigo,
        public ?string $classe_nome,
        public ?float $cotacao,
        public ?float $percentual,
        public ?float $valor_ativo,
        public ?float $custo_total_ativo
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            Arr::get($data, 'id'),
            Arr::get($data, 'user_id'),
            Arr::get($data, 'ativo_id'),
            Arr::get($data, 'quantidade_saldo'),
            Arr::get($data, 'preco_medio'),
            Arr::get($data, 'codigo'),
            Arr::get($data, 'classe_nome'),
            Arr::get($data, 'cotacao'),
            Arr::get($data, 'percentual'),
            Arr::get($data, 'valor_ativo'),
            Arr::get($data, 'custo_total_ativo')
        );
    }
}
