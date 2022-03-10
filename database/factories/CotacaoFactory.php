<?php

namespace Database\Factories;

use App\Domain\Ativo\Models\Ativo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CotacaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ativo_id'=> Ativo::factory()->create()->id,
            'moeda_ref' => 'R$',
            'preco' => $this->faker->randomFloat(8, 8, 8)
        ];
    }
}
