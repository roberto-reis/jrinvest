<?php

namespace Database\Factories;

use App\Domain\ClasseAtivo\Models\ClasseAtivo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseAtivoFactory extends Factory
{
    protected $model = ClasseAtivo::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->userName(),
            'descricao' => $this->faker->text(30),
        ];
    }
}
