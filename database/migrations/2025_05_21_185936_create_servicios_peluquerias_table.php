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
        Schema::create('servicios_peluqueria', function (Blueprint $table) {
            $table->unsignedBigInteger('id_servicio');
            $table->unsignedBigInteger('id_peluqueria');
            $table->decimal('precio', 4, 2);
            $table->integer('duracion')->comment('En minutos');
            $table->timestamps();

            $table->foreign('id_servicio')->references('id')->on('servicios');
            $table->foreign('id_peluqueria')->references('id')->on('peluquerias');

            $table->primary(['id_servicio', 'id_peluqueria']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios_peluqueria');
    }
};
