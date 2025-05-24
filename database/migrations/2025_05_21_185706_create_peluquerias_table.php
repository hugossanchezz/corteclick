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
            $table->string('descripcion', 200)->nullable();
            $table->string('direccion', 200);
            $table->unsignedBigInteger('localidad')->nullable();
            $table->string('email', 150)->unique();
            $table->string('telefono', 20)->nullable();
            $table->enum('tipo', ['BARBERIA', 'PELUQUERIA', 'UNISEX'])->nullable();
            $table->decimal('valoracion', 3, 2)->nullable()->comment('Media de las valoraciones del establecimiento');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('localidad')->references('id')->on('localidades');
            $table->foreign('user_id')->references('id')->on('users');
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
