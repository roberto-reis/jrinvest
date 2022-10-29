<?php

use App\Domain\ClasseAtivo\Models\ClasseAtivo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $classesAtivos = [
        [
            "nome" => "FII",
            "descricao" => "Fundos Imobiliários"
        ],
        [
            "nome" => "Ações",
            "descricao" => "Ações - B3"
        ],
        [
            "nome" => "Stablecoin",
            "descricao" => "Criptoativos"
        ],
        [
            "nome" => "Cripto",
            "descricao" => "Criptomoedas"
        ]
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->classesAtivos as $classeAtivo) {
            ClasseAtivo::create($classeAtivo);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->classesAtivos as $classeAtivo) {
            ClasseAtivo::where('nome', $classeAtivo['nome'])->delete();
        }
    }
};
