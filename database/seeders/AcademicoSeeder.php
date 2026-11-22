<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AcademicoSeeder extends Seeder
{
    public function run(): void
    {
        // Profesor
        User::create([
            'name' => 'Profesor',
            'email' => 'profesor@sistema.com',
            'password' => 'Profesor12345',
            'role' => 'profesor',
        ]);

        // Alumnos con distintas calificaciones para probar cada letra
        $alumnos = [
            ['Ana Lopez', 'ana@sistema.com', 2, 7.0],     // NA
            ['Luis Perez', 'luis@sistema.com', 0, 8.5],   // SA
            ['Maria Ruiz', 'maria@sistema.com', 1, 9.5],  // DE
            ['Jose Soto', 'jose@sistema.com', 0, 10],     // AU
        ];

        foreach ($alumnos as [$nombre, $correo, $faltas, $calificacion]) {
            $user = User::create([
                'name' => $nombre,
                'email' => $correo,
                'password' => 'Alumno12345',
                'role' => 'alumno',
            ]);

            $user->alumno()->create([
                'faltas' => $faltas,
                'calificacion' => $calificacion,
            ]);
        }
    }
}