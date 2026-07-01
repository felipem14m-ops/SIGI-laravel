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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id_usuario')->autoIncrement();
            $table->tinyInteger('id_rol');
            $table->string('nombre', 100);
            $table->string('numeroIdentificacion', 20)->unique('uq_usuarios_identificacion');
            $table->string('email', 100)->unique('uq_usuarios_email');
            $table->string('contrasena', 255);
            $table->tinyInteger('activo')->default(1);
            $table->tinyInteger('intentos_fallidos')->default(0);
            $table->dateTime('bloqueado_hasta')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['id_rol', 'activo'], 'idx_rol_activo');

            $table->foreign('id_rol', 'fk_usuarios_rol')
                  ->references('id_rol')
                  ->on('roles');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
