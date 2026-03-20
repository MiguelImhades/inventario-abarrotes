<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Esto permite que podamos guardar datos en estas columnas desde un formulario
    protected $fillable = [
        'nombre', 
        'marca', 
        'categoria', 
        'existencias', 
        'precio_compra', 
        'precio_venta'
    ];
}