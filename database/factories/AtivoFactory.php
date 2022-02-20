<?php

namespace Database\Factories;

use App\Models\ClasseAtivo;
use Illuminate\Database\Eloquent\Factories\Factory;

class AtivoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'codigo'=>$this->faker->unique()->currencyCode(),
            // 'classe_ativo_id'=> ClasseAtivo::factory()->create()->id,
            'descricao'=>$this->faker->text(50),
            'setor'=>$this->faker->text(30)
        ];
    }
}
