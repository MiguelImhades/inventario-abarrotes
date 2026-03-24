<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Esta función sirve para MOSTRAR la tabla con productos
    public function index()
    {
        $productos = Producto::all(); 
        return view('productos.index', compact('productos'));
    }

    // Esta función sirve para GUARDAR el nuevo producto
    public function store(Request $request)
    {
        // Validamos que no envíes campos vacíos
        $request->validate([
            'nombre' => 'required',
            'marca' => 'required',
            'categoria_id' => 'required',
            'existencias' => 'required|integer',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
        ]);

        // Creamos el producto en la base de datos de Postgres
        Producto::create($request->all());

        // Nos regresa a la pantalla principal con un aviso
        return redirect()->route('productos.index')->with('success', '¡Producto registrado con éxito!');
    }
}