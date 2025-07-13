<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cita;
use Carbon\Carbon;

class CitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define appointments for the week of June 2–6, 2025
        $appointments = [
            [
                'id_usuario' => 1,
                'id_peluqueria' => 1,
                'id_servicio' => 1,
                'fecha' => '2025-06-05',
                'hora_inicio' => '09:00:00',
                'hora_fin' => '10:30:00',
                'estado' => 'CONFIRMADA',
                'valoracion' => null,
                'puntuacion' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_usuario' => 1,
                'id_peluqueria' => 1,
                'id_servicio' => 1,
                'fecha' => '2025-06-13',
                'hora_inicio' => '10:30:00',
                'hora_fin' => '11:00:00',
                'estado' => 'CONFIRMADA',
                'valoracion' => null,
                'puntuacion' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_usuario' => 1,
                'id_peluqueria' => 1,
                'id_servicio' => 1,
                'fecha' => '2025-06-12',
                'hora_inicio' => '11:00:00',
                'hora_fin' => '11:30:00',
                'estado' => 'CONFIRMADA',
                'valoracion' => null,
                'puntuacion' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_usuario' => 1,
                'id_peluqueria' => 1,
                'id_servicio' => 1,
                'fecha' => '2025-06-11',
                'hora_inicio' => '09:30:00',
                'hora_fin' => '10:00:00',
                'estado' => 'CONFIRMADA',
                'valoracion' => null,
                'puntuacion' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_usuario' => 1,
                'id_peluqueria' => 1,
                'id_servicio' => 1,
                'fecha' => '2025-06-10',
                'hora_inicio' => '13:00:00',
                'hora_fin' => '13:30:00',
                'estado' => 'CONFIRMADA',
                'valoracion' => null,
                'puntuacion' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert appointments into the citas table
        foreach ($appointments as $appointment) {
            Cita::create($appointment);
        }
    }
}