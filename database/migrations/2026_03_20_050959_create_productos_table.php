<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');          // Nombre del producto
            $table->string('marca');           // Marca (Abarrotes)
            $table->integer('existencias');    // Cantidad actual
            $table->decimal('precio', 8, 2);   // Precio con decimales (ej: 15.50)
            $table->timestamps();              // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};