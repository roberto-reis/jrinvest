<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Domain\Ativo\Models\Ativo;
use App\Models\RebalanceamentoAtivo;
use App\Models\RebalanceamentoClasse;
use App\Domain\Operacao\Models\Operacao;
use App\Domain\ClasseAtivo\Models\ClasseAtivo;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $users = User::factory(5)->create();        
        

        $users->each(function($user) {

            $classeAtivo = ClasseAtivo::factory()->create();

            $ativo = Ativo::factory()->create([
                'classe_ativo_id' => $classeAtivo->id
            ]);
            
            RebalanceamentoClasse::factory()->create([
                'user_id' => $user->id,
                'classe_ativo_id' => $classeAtivo->id
            ]);

            RebalanceamentoAtivo::factory()->create([
                'user_id' => $user->id,
                'ativo_id' => $ativo->id
            ]);

            Operacao::factory(5)->create([
                'user_id' => $user->id,
                'ativo_id' => $ativo->id
            ]);

        });



    }
}
