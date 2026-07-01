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
        Schema::create('detalleventa', function (Blueprint $table) {
            $table->integer('id_detalle')->autoIncrement();
            $table->integer('id_venta');
            $table->integer('id_producto');
            $table->integer('cantidad');
            $table->decimal('precioUnitario', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index('id_venta', 'idx_detalle_venta');
            $table->index('id_producto', 'idx_detalle_producto');

            $table->foreign('id_venta', 'fk_detalle_venta')
                  ->references('id_venta')
                  ->on('venta');

            $table->foreign('id_producto', 'fk_detalle_producto')
                  ->references('id_producto')
                  ->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalleventa');
    }
};
