<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ativo;
use App\Models\Operacao;
use App\Models\ClasseAtivo;
use App\Models\RebalanceamentoAtivo;
use App\Models\RebalanceamentoClasse;
use Illuminate\Database\Seeder;

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
