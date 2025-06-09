<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PeluqueriasSolicitudesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        DB::table('peluquerias_solicitudes')->insert([
            [
                'estado' => 'PENDIENTE',
                'fecha' => now(),
                'nombre' => 'Barbería Nueva Imagen',
                'descripcion' => 'Barbería moderna con enfoque en diseño de barba y corte urbano.',
                'direccion' => $faker->streetAddress,
                'localidad' => 1,
                'email' => 'nuevaimagen@gmail.com',
                'telefono' => $faker->regexify('[6-9]{1}[0-9]{8}'),
                'tipo' => 'BARBERIA',
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estado' => 'PENDIENTE',
                'fecha' => now()->subDays(3),
                'nombre' => 'Estilo & Color',
                'descripcion' => 'Peluquería especializada en coloración avanzada y tendencias de corte.',
                'direccion' => $faker->streetAddress,
                'localidad' => 4,
                'email' => 'estiloycolor@gmail.com',
                'telefono' => $faker->regexify('[6-9]{1}[0-9]{8}'),
                'tipo' => 'PELUQUERIA',
                'user_id' => 3,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ]
        ]);
    }
}
