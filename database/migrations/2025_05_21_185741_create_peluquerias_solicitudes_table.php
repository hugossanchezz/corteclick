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
        Schema::create('peluquerias_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->enum('estado', ['PENDIENTE', 'APROBADA', 'RECHAZADA']);
            $table->dateTime('fecha');
            $table->string('nombre', 100);
            $table->string('descripcion', 200);
            $table->string('direccion', 150);
            $table->unsignedBigInteger('localidad')->nullable();
            $table->string('email', 150)->unique();
            $table->string('telefono', 20);
            $table->enum('tipo', ['BARBERIA', 'PELUQUERIA', 'UNISEX']);
            $table->string('contrasenia');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('localidad')->references('id')->on('localidades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_peluquerias');
    }
};
