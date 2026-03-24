<?php

use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

// Redirigir la página principal directamente al inventario
Route::get('/', [ProductoController::class, 'index'])->name('productos.index');

// Ruta para ver la tabla (opcional si ya usas la de arriba)
Route::get('/productos', [ProductoController::class, 'index']);

// --- RUTAS PARA QUE LOS BOTONES FUNCIONEN ---

// 1. Guardar el "Nuevo Producto"
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');

// 2. Botón "Surtir" (+50 existencias)
Route::patch('/productos/{id}/surtir', [ProductoController::class, 'surtir'])->name('productos.surtir');

// 3. Botón de "Guardar Cambios" al Editar
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');

// 4. Botón de la basura (Eliminar)
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');