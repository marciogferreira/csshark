<?php

use App\Models\Cargos;
use App\Models\ModalidadesModel;
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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com.br',
            'password' => Hash::make('123456'),
            'role' => '1',
        ]);

        User::create([
            'name' => 'Aluno',
            'email' => 'aluno@aluno.com.br',
            'password' => Hash::make('123456'),
            'role' => '2',
        ]);

        Cargos::create([
            'name' => 'Professor',
        ]);

        for($i = 1; $i <= 20; $i++) {
            ModalidadesModel::create([
                'nome' => 'Modalidade -'.($i+1)
            ]);
        }

        
    }
}
