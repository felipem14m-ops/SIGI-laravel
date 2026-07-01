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
        Schema::create('venta', function (Blueprint $table) {
            $table->integer('id_venta')->autoIncrement();
            $table->integer('id_usuario');
            $table->integer('id_metodo');
            $table->timestamp('fecha_venta')->useCurrent();
            $table->decimal('total', 12, 2);
            $table->enum('estado', ['completada', 'anulada'])->default('completada');
            $table->timestamps();

            $table->index('fecha_venta', 'idx_venta_fecha');
            $table->index('estado', 'idx_venta_estado');
            $table->index('id_usuario', 'idx_venta_usuario');

            $table->foreign('id_usuario', 'fk_venta_usuario')
                  ->references('id_usuario')
                  ->on('users');

            $table->foreign('id_metodo', 'fk_venta_metodo')
                  ->references('id_metodo')
                  ->on('metodos_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};
