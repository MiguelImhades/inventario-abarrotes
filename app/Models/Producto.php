<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre', 
        'marca', 
        'categoria_id', // <-- CAMBIADO de 'categoria' a 'categoria_id'
        'existencias', 
        'precio_compra', 
        'precio_venta'
    ];

    // Esta función es la que "conecta" con la tabla de categorías
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}