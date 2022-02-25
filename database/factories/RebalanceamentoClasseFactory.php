<?php

namespace Database\Factories;

use App\Models\ClasseAtivo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RebalanceamentoClasseFactory extends Factory
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
            // 'classe_ativo_id' => ClasseAtivo::factory()->create()->id,
            'porcentagem' => $this->faker->randomFloat(2, 0, 50),
        ];
    }
}
