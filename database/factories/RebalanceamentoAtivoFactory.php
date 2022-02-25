<?php

namespace Database\Factories;

use App\Models\Ativo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RebalanceamentoAtivoFactory extends Factory
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
            'porcentagem' => $this->faker->randomFloat(2, 0, 50),
        ];
    }
}

