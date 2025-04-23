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
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellidos', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->unsignedBigInteger('localidad')->nullable();
            $table->binary('foto')->nullable();

            $table->foreign('localidad')->references('id')->on('localidades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nombre');
            $table->dropColumn('apellidos');
            $table->dropColumn('telefono');
            $table->dropColumn('contrasenia');
            $table->dropColumn('codigo_postal');
            $table->dropColumn('foto');
        });
    }
};
