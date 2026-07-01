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
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->integer('id_metodo')->autoIncrement();
            $table->string('nombre', 50)->unique('uq_metodos_nombre');
            $table->tinyInteger('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
    }
};
