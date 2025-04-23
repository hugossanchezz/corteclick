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
        Schema::create('peluquerias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('descripcion', 200);
            $table->unsignedBigInteger('localidad')->nullable();
            $table->string('email', 150);
            $table->string('telefono', 20)->nullable();
            $table->enum('tipo', ['BARBERIA', 'PELUQUERIA', 'UNISEX'])->nullable();
            $table->string('contrasenia',255);// Encriptada en sha1

            $table->foreign('localidad')->references('id')->on('localidades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peluquerias');
    }
};
