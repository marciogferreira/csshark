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
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@admin.com.br',
        //     'password' => Hash::make('123456'),
        //     'role' => '1',
        // ]);

        // User::create([
        //     'name' => 'Ronielle',
        //     'email' => 'ronielle@parlumin.com.br',
        //     'password' => Hash::make('ronielle@123'),
        //     'role' => '1',
        // ]);

        // User::create([
        //     'name' => 'Jéssica',
        //     'email' => 'rh.parlumin@gmail.com',
        //     'password' => Hash::make('jessica@123'),
        //     'role' => '1',
        // ]);

        // Cargos::create([
        //     'name' => 'Professor',
        // ]);

        for($i = 1; $i <= 20; $i++) {
            ModalidadesModel::create([
                'nome' => 'Modalidade -'.($i+1)
            ]);
        }
    }
}
