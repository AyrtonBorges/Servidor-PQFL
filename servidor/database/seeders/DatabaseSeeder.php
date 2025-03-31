<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{


    public function run(): void
    {
        // User::factory(10)->create();

        Usuarios::create([
            'usuario' => 'Usuarios1',
            'senha' => Hash::make('12345678'),
        ]);
    }
}
