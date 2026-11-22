<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = collect(['Electrónica', 'Hogar', 'Papelería', 'Deportes'])
            ->map(fn ($nombre) => Categoria::create(['nombre' => $nombre]));

        for ($i = 1; $i <= 30; $i++) {
            $existencias = $i % 5 === 0 ? 0 : rand(1, 50);

            $producto = new Producto([
                'nombre' => "Producto $i",
                'descripcion' => "Descripción del producto $i",
                'categoria_id' => $categorias[($i - 1) % 4]->id,
                'precio' => rand(10, 999) + rand(0, 99) / 100,
                'existencias' => $existencias,
                'estado' => $existencias > 0 ? 'Disponible' : 'Agotado',
            ]);

            $producto->created_at = now()->subDays($i);
            $producto->save();
        }
    }
}