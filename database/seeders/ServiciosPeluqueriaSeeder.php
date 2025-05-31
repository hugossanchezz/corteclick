<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiciosPeluqueriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('servicios_peluqueria')->insert([
            ['id_peluqueria' => 1, 'id_servicio' => 1, 'precio' => 15.00, 'duracion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 1, 'id_servicio' => 2, 'precio' => 25.00, 'duracion' => 45, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 1, 'id_servicio' => 11, 'precio' => 20.00, 'duracion' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 1, 'id_servicio' => 12, 'precio' => 35.00, 'duracion' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 1, 'id_servicio' => 15, 'precio' => 25.00, 'duracion' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 1, 'id_servicio' => 21, 'precio' => 10.00, 'duracion' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 1, 'id_servicio' => 23, 'precio' => 12.00, 'duracion' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 2, 'id_servicio' => 1, 'precio' => 12.00, 'duracion' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 2, 'id_servicio' => 2, 'precio' => 20.00, 'duracion' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 2, 'id_servicio' => 4, 'precio' => 15.00, 'duracion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 2, 'id_servicio' => 9, 'precio' => 18.00, 'duracion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 2, 'id_servicio' => 21, 'precio' => 8.00, 'duracion' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 3, 'id_servicio' => 11, 'precio' => 18.00, 'duracion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 3, 'id_servicio' => 12, 'precio' => 40.00, 'duracion' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 3, 'id_servicio' => 16, 'precio' => 30.00, 'duracion' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 3, 'id_servicio' => 15, 'precio' => 22.00, 'duracion' => 45, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 3, 'id_servicio' => 21, 'precio' => 12.00, 'duracion' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 4, 'id_servicio' => 12, 'precio' => 38.00, 'duracion' => 65, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 4, 'id_servicio' => 14, 'precio' => 50.00, 'duracion' => 90, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 4, 'id_servicio' => 15, 'precio' => 25.00, 'duracion' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 4, 'id_servicio' => 18, 'precio' => 60.00, 'duracion' => 120, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 4, 'id_servicio' => 21, 'precio' => 10.00, 'duracion' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 5, 'id_servicio' => 1, 'precio' => 14.00, 'duracion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 5, 'id_servicio' => 4, 'precio' => 16.00, 'duracion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 5, 'id_servicio' => 9, 'precio' => 20.00, 'duracion' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 5, 'id_servicio' => 21, 'precio' => 9.00, 'duracion' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 5, 'id_servicio' => 23, 'precio' => 10.00, 'duracion' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 6, 'id_servicio' => 1, 'precio' => 16.00, 'duracion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 6, 'id_servicio' => 11, 'precio' => 22.00, 'duracion' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 6, 'id_servicio' => 12, 'precio' => 40.00, 'duracion' => 65, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 6, 'id_servicio' => 15, 'precio' => 28.00, 'duracion' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 6, 'id_servicio' => 21, 'precio' => 12.00, 'duracion' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 6, 'id_servicio' => 23, 'precio' => 14.00, 'duracion' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 7, 'id_servicio' => 11, 'precio' => 20.00, 'duracion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 7, 'id_servicio' => 12, 'precio' => 42.00, 'duracion' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 7, 'id_servicio' => 14, 'precio' => 55.00, 'duracion' => 90, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 7, 'id_servicio' => 16, 'precio' => 35.00, 'duracion' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 7, 'id_servicio' => 21, 'precio' => 11.00, 'duracion' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 8, 'id_servicio' => 1, 'precio' => 13.00, 'duracion' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 8, 'id_servicio' => 2, 'precio' => 22.00, 'duracion' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 8, 'id_servicio' => 4, 'precio' => 14.00, 'duracion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 8, 'id_servicio' => 9, 'precio' => 19.00, 'duracion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 8, 'id_servicio' => 21, 'precio' => 8.00, 'duracion' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 9, 'id_servicio' => 1, 'precio' => 15.00, 'duracion' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 9, 'id_servicio' => 11, 'precio' => 20.00, 'duracion' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 9, 'id_servicio' => 12, 'precio' => 38.00, 'duracion' => 65, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 9, 'id_servicio' => 15, 'precio' => 25.00, 'duracion' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 9, 'id_servicio' => 21, 'precio' => 10.00, 'duracion' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 9, 'id_servicio' => 23, 'precio' => 12.00, 'duracion' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 10, 'id_servicio' => 1, 'precio' => 14.00, 'duracion' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 10, 'id_servicio' => 4, 'precio' => 18.00, 'duracion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 10, 'id_servicio' => 9, 'precio' => 20.00, 'duracion' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 10, 'id_servicio' => 21, 'precio' => 9.00, 'duracion' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['id_peluqueria' => 10, 'id_servicio' => 23, 'precio' => 11.00, 'duracion' => 20, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}