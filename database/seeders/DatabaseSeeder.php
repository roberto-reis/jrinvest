<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ativo;
use App\Models\Operacao;
use App\Models\ClasseAtivo;
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

        $users = User::factory(4)->create();        
        

        $users->each(function($user) {

            $classeAtivo = ClasseAtivo::factory()->create();

            $ativo = Ativo::factory()->create([
                'classe_ativo_id' => $classeAtivo->id,
            ]);   

            Operacao::factory(5)->create([
                'user_id' => $user->id,
                'ativo_id' => $ativo->id
            ]);

        });



    }
}
