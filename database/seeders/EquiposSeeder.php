<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\User;
use Illuminate\Database\Seeder;

class EquiposSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            Equipo::create([
                'nombre' => "Equipo $i",
                'descripcion' => "Computadora del laboratorio, lugar $i",
            ]);
        }

        User::create([
            'name' => 'Estudiante Uno',
            'email' => 'estudiante1@sistema.com',
            'password' => 'Estudiante12345',
            'role' => 'estudiante',
        ]);

        User::create([
            'name' => 'Estudiante Dos',
            'email' => 'estudiante2@sistema.com',
            'password' => 'Estudiante12345',
            'role' => 'estudiante',
        ]);
    }
}