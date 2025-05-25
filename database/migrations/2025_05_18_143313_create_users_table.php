<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla users con campos extendidos
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->string('apellidos', 200)->nullable();
            $table->string('telefono', 9)->nullable();
            $table->unsignedBigInteger('localidad')->nullable();
            $table->binary('foto')->nullable();
            $table->unsignedBigInteger('rol_id')->nullable();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->timestamps();

            // Claves foráneas
            $table->foreign('localidad')->references('id')->on('localidades');
            $table->foreign('rol_id')->references('id')->on('roles');
        });

        // Tabla password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabla sessions
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
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
