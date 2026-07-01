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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->integer('id_proveedor')->autoIncrement();
            $table->string('nombre', 100);
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('empresa', 100)->nullable();
            $table->tinyInteger('activo')->default(1);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
