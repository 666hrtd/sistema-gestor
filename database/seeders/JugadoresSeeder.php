<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class JugadoresSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([1, 2, 3] as $n) {
            User::create([
                'name' => "Jugador $n",
                'email' => "jugador$n@sistema.com",
                'password' => 'Jugador12345',
                'role' => 'jugador',
            ]);
        }
    }
}