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
        Schema::create('movimiento_inventario', function (Blueprint $table) {
            $table->integer('id_movimiento')->autoIncrement();
            $table->integer('id_producto');
            $table->integer('id_usuario');
            $table->integer('id_tipo_movimiento');
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_resultante');
            $table->enum('origen', ['manual', 'automatico'])->default('manual');
            $table->text('motivo')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();

            $table->index('id_producto', 'idx_mov_producto');
            $table->index('fecha', 'idx_mov_fecha');
            $table->index('id_usuario', 'idx_mov_usuario');

            $table->foreign('id_producto', 'fk_movimiento_producto')
                  ->references('id_producto')
                  ->on('productos');

            $table->foreign('id_usuario', 'fk_movimiento_usuario')
                  ->references('id_usuario')
                  ->on('users');

            $table->foreign('id_tipo_movimiento', 'fk_movimiento_tipo')
                  ->references('id_tipo')
                  ->on('tipos_movimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_inventario');
    }
};
