<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('marca');
        
        // Relación con categorías:
        $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
        
        $table->integer('existencias')->default(0);
        $table->decimal('precio_compra', 8, 2);
        $table->decimal('precio_venta', 8, 2);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};