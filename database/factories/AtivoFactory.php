<?php

namespace Database\Factories;

use App\Domain\Ativo\Models\Ativo;
use App\Domain\ClasseAtivo\Models\ClasseAtivo;
use Illuminate\Database\Eloquent\Factories\Factory;

class AtivoFactory extends Factory
{

    protected $model = Ativo::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'codigo' => $this->faker->userName(),
            // 'classe_ativo_id'=> ClasseAtivo::factory()->create()->id,
            'nome' => $this->faker->text(50),
            'setor' => $this->faker->text(30)
        ];
    }
}
