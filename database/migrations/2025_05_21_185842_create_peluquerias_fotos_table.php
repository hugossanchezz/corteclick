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
        Schema::create('peluquerias_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_peluqueria');
            $table->timestamps();

            $table->foreign('id_peluqueria')->references('id')->on('peluquerias');
        });

        DB::statement("ALTER TABLE peluquerias_fotos ADD imagen LONGBLOB NULL AFTER id_peluqueria");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peluquerias_fotos');
    }
};
