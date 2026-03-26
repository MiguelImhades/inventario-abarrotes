<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        // Esto está bien, pero cuidado: se ejecuta cada vez que entras a la lista
        Producto::where('existencias', '<', 5)->increment('existencias', 50);

        $productos = Producto::with('categoria')->orderBy('id', 'desc')->get();
        $categorias = Categoria::orderBy('nombre', 'asc')->get(); 
        
        return view('productos.index', compact('productos', 'categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|min:2|max:30', 
            'marca'        => 'required|string|min:2|max:30', 
            'categoria_id' => 'required|exists:categorias,id',
            'existencias'  => 'required|integer|min:0|max:10000',
            'precio_compra'=> 'required|numeric|min:0.01|max:999999',
            'precio_venta' => 'required|numeric|min:0.01|gt:precio_compra|max:999999',
        ], [
            'precio_venta.gt' => 'El precio de venta debe ser mayor al precio de compra.'
        ]);

        // Usamos fillable o guardamos directamente
        Producto::create($data);
        
        return redirect()->route('productos.index')->with('success', '¡Producto registrado con éxito!');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|min:2|max:30', 
            'marca'        => 'required|string|min:2|max:30',
            // Agregamos categoria_id aquí también por si se edita
            'categoria_id' => 'required|exists:categorias,id', 
            'existencias'  => 'required|integer|min:0|max:10000',
            'precio_compra'=> 'required|numeric|min:0.01|max:999999',
            'precio_venta' => 'required|numeric|min:0.01|gt:precio_compra|max:999999',
        ], [
            'precio_venta.gt' => 'Error: El precio de venta debe ser mayor al de compra.'
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($data);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        Producto::findOrFail($id)->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado');
    }

    public function surtir($id)
    {
        Producto::findOrFail($id)->increment('existencias', 50);
        return redirect()->route('productos.index')->with('success', 'Stock actualizado correctamente.');
    }
}