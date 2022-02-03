<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Ativo;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperacaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'user_id' => User::factory()->create()->id,
            // 'ativo_id' => Ativo::factory()->create()->id,
            'tipo_operacao' => $this->faker->randomElement($array = ['compra', 'venda'], $count = 1),
            'quantidade' => $this->faker->randomFloat(8, 1, 100),
            'cotacao_preco' => $this->faker->randomFloat(8, 1, 250),
            'corretora' => $this->faker->name()
        ];
    }
}
