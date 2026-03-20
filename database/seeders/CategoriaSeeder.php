<?php

namespace Database\Seeders;

use App\Models\Categoria; // IMPORTANTE: Importar el modelo
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Lácteos', 
            'Sabritas', 
            'Galletas', 
            'Panes', 
            'Bebidas', 
            'Limpieza del Hogar', 
            'Higiene Personal', 
            'Farmacia', 
            'Carnes y Embutidos', 
            'Abarrotes (Arroz, Granos, etc.)', 
            'Frutas y Verduras'
        ];

        foreach ($categorias as $nombre) {
            Categoria::create([
                'nombre' => $nombre
            ]);
        }
    }
}