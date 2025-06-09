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
        Schema::create('peluquerias_fotos_temporales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_peluqueria_solicitud');
            $table->timestamps();

            $table->foreign('id_peluqueria_solicitud')->references('id')->on('peluquerias_solicitudes');
        });

        DB::statement("ALTER TABLE peluquerias_fotos_temporales ADD imagen LONGBLOB NULL AFTER id_peluqueria_solicitud");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peluquerias_fotos_temporales');
    }
};
