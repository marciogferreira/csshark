<?php

use App\Models\AlunosModel;
use App\Models\Cargos;
use App\Models\ModalidadesModel;
use App\Models\TreinosModel;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@admin.com.br',
        //     'password' => Hash::make('091188'),
        //     'role' => '1',
        // ]);

        // User::create([
        //     'name' => 'Danilson ',
        //     'email' => 'danilson@shark.com.br',
        //     'password' => Hash::make('123456'),
        //     'role' => '1',
        // ]);

        // User::create([
        //     'name' => 'Aluno',
        //     'email' => 'aluno@aluno.com.br',
        //     'password' => Hash::make('091188'),
        //     'role' => '2',
        // ]);

        // Cargos::create([
        //     'name' => 'Professor',
        // ]);


        AlunosModel::create([
            'nome' => 'Max',
            'email' => 'max@max.com.br',
            'cpf' => '125484958911',
            'senha' => '123123'
        ]);

        AlunosModel::create([
            'nome' => 'Max 2',
            'email' => 'max2@max.com.br',
            'cpf' => '12548495813',
            'senha' => '123123'
        ]);

        TreinosModel::create([
            'aluno_id' => 1,
            'data' => "{}",
        ]);

        TreinosModel::create([
            'aluno_id' => 2,
            'data' => "{}",
        ]);

        // for($i = 1; $i <= 20; $i++) {
        //     ModalidadesModel::create([
        //         'nome' => 'Modalidade -'.($i+1)
        //     ]);
        // }

        
    }
}
