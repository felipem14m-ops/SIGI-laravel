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
            $table->integer('id_producto')->autoIncrement();
            $table->string('codigoUnico', 50)->unique('uq_productos_codigo');
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->integer('id_categoria');
            $table->integer('id_proveedor')->nullable();
            $table->decimal('precio_venta', 10, 2);
            $table->decimal('precio_costo', 10, 2);
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_actual')->default(0);
            $table->timestamp('fechaCreacion')->useCurrent();
            $table->date('fechaVencimiento')->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'agotado'])->default('activo');
            $table->string('imagen', 255)->nullable();
            $table->timestamps();

            $table->index('id_categoria', 'idx_categoria');
            $table->index('id_proveedor', 'idx_proveedor');
            $table->index('estado', 'idx_estado');
            $table->index('fechaVencimiento', 'idx_vencimiento');

            $table->foreign('id_categoria', 'fk_productos_categoria')
                  ->references('id_categoria')
                  ->on('categorias');

            $table->foreign('id_proveedor', 'fk_productos_proveedor')
                  ->references('id_proveedor')
                  ->on('proveedores');
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
