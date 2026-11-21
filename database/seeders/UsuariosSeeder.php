<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'damtzh07@gmail.com',
            'password' => 'Admin12345',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Empleado',
            'email' => 'empleado@sistema.com',
            'password' => 'Empleado12345',
            'role' => 'empleado',
        ]);
    }
}