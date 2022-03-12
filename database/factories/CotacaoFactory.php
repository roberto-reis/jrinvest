<?php

namespace Database\Factories;

use App\Domain\Ativo\Models\Ativo;
use App\Domain\Cotacao\Models\Cotacao;
use Illuminate\Database\Eloquent\Factories\Factory;

class CotacaoFactory extends Factory
{
    protected $model = Cotacao::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'ativo_id'=> Ativo::factory()->create()->id,
            'moeda_ref' => 'R$',
            'preco' => $this->faker->randomFloat(8, 8, 8)
        ];
    }
}
