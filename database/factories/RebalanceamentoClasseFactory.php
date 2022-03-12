<?php

namespace Database\Factories;

use App\Domain\User\Models\User;
use App\Domain\ClasseAtivo\Models\ClasseAtivo;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Rebalanceamento\Models\RebalanceamentoClasse;

class RebalanceamentoClasseFactory extends Factory
{
    protected $model = RebalanceamentoClasse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            // 'classe_ativo_id' => ClasseAtivo::factory()->create()->id,
            'percentual' => $this->faker->randomFloat(2, 0, 50),
        ];
    }
}
