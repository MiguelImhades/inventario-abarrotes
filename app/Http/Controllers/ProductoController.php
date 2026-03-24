<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        // SURTIDO AUTOMÁTICO
        $productosBajos = Producto::where('existencias', '<', 5)->get();
        
        $huboCambios = false;
        foreach ($productosBajos as $p) {
            $p->existencias += 50;
            $p->save();
            $huboCambios = true;
        }

        // Si surtió algo, recargamos para que el usuario vea los números actualizados
        if ($huboCambios) {
            return redirect()->route('productos.index')->with('success', 'Stock actualizado automáticamente.');
        }

        $productos = Producto::with('categoria')->orderBy('id', 'desc')->get();
        $categorias = Categoria::all(); 
        
        return view('productos.index', compact('productos', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:30', // AHORA 30
            'marca'        => 'required|string|max:30', 
            'categoria_id' => 'required',
            'existencias'  => 'required|integer',
            'precio_compra'=> 'required|numeric',
            'precio_venta' => 'required|numeric',
        ]);

        Producto::create($request->all());
        return redirect()->route('productos.index')->with('success', '¡Producto registrado con éxito!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'       => 'required|string|max:30', // AHORA 30
            'marca'        => 'required|string|max:30',
            'categoria_id' => 'required',
            'existencias'  => 'required|integer',
            'precio_compra'=> 'required|numeric',
            'precio_venta' => 'required|numeric',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($request->all());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado');
    }

    public function surtir($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->existencias += 50;
        $producto->save();
        return redirect()->route('productos.index')->with('success', 'Se han surtido 50 unidades a ' . $producto->nombre);
    }
}