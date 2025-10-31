<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class CitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usuarios clientes disponibles (ids 3 a 7, rol 3)
        $usuariosClientes = [3, 4, 5, 6, 7];

        // Frases para valoraciones basadas en puntuación
        $valoracionesPositivas = [
            "Excelente servicio, muy recomendable.",
            "Todo genial, el trato y el servicio han sido muy buenos.",
            "Me encantó el resultado, volveré pronto.",
            "Profesionales y atentos, gran experiencia.",
            "Superó mis expectativas, ¡5 estrellas!"
        ];
        $valoracionesNeutrales = [
            "Servicio aceptable, nada del otro mundo.",
            "Bien, pero podría mejorar en algunos aspectos.",
            "Correcto, sin quejas mayores.",
            "Estuvo okay, cumplió con lo esperado."
        ];
        $valoracionesNegativas = [
            "No quedé satisfecho, hay que mejorar.",
            "El servicio fue regular, esperaba más.",
            "Hubo algunos problemas, no lo recomiendo.",
            "Decepcionante, no volveré."
        ];

        // Asumimos peluquerías del 1 al 20 (basado en el seeder proporcionado)
        for ($id_peluqueria = 1; $id_peluqueria <= 20; $id_peluqueria++) {
            // Obtener servicios disponibles para esta peluquería con sus duraciones
            $servicios = DB::table('servicios_peluqueria')
                ->where('id_peluqueria', $id_peluqueria)
                ->pluck('duracion', 'id_servicio')
                ->toArray();

            if (empty($servicios)) {
                continue; // Si no hay servicios, saltar
            }

            // Generar 3 a 5 citas terminadas (pasadas) con valoración
            $numTerminadas = rand(3, 5);
            for ($i = 0; $i < $numTerminadas; $i++) {
                $id_usuario = Arr::random($usuariosClientes);
                $id_servicio = array_rand($servicios);
                $duracion = $servicios[$id_servicio];

                // Fecha pasada (entre 1 y 60 días atrás)
                $fecha = Carbon::now()->subDays(rand(1, 60))->format('Y-m-d');

                // Hora inicio aleatoria entre 09:00 y 18:00, en intervalos de 15 min
                $horaInicioHour = rand(9, 17);
                $horaInicioMin = rand(0, 3) * 15;
                $hora_inicio = sprintf('%02d:%02d:00', $horaInicioHour, $horaInicioMin);

                // Calcular hora fin
                $hora_fin = Carbon::parse($fecha . ' ' . $hora_inicio)->addMinutes($duracion)->format('H:i:s');

                // Puntuación aleatoria 1-5
                $puntuacion = rand(1, 5);

                // Valoración basada en puntuación
                if ($puntuacion >= 4) {
                    $valoracion = Arr::random($valoracionesPositivas);
                } elseif ($puntuacion == 3) {
                    $valoracion = Arr::random($valoracionesNeutrales);
                } else {
                    $valoracion = Arr::random($valoracionesNegativas);
                }

                Cita::create([
                    'id_usuario' => $id_usuario,
                    'id_peluqueria' => $id_peluqueria,
                    'id_servicio' => $id_servicio,
                    'fecha' => $fecha,
                    'hora_inicio' => $hora_inicio,
                    'hora_fin' => $hora_fin,
                    'estado' => 'TERMINADA',
                    'valoracion' => $valoracion,
                    'puntuacion' => $puntuacion,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // Generar 2 a 4 citas futuras (confirmadas)
            $numFuturas = rand(2, 4);
            for ($i = 0; $i < $numFuturas; $i++) {
                $id_usuario = Arr::random($usuariosClientes);
                $id_servicio = array_rand($servicios);
                $duracion = $servicios[$id_servicio];

                // Fecha futura (entre 1 y 30 días adelante)
                $fecha = Carbon::now()->addDays(rand(1, 30))->format('Y-m-d');

                // Hora inicio aleatoria entre 09:00 y 18:00, en intervalos de 15 min
                $horaInicioHour = rand(9, 17);
                $horaInicioMin = rand(0, 3) * 15;
                $hora_inicio = sprintf('%02d:%02d:00', $horaInicioHour, $horaInicioMin);

                // Calcular hora fin
                $hora_fin = Carbon::parse($fecha . ' ' . $hora_inicio)->addMinutes($duracion)->format('H:i:s');

                Cita::create([
                    'id_usuario' => $id_usuario,
                    'id_peluqueria' => $id_peluqueria,
                    'id_servicio' => $id_servicio,
                    'fecha' => $fecha,
                    'hora_inicio' => $hora_inicio,
                    'hora_fin' => $hora_fin,
                    'estado' => 'CONFIRMADA',
                    'valoracion' => null,
                    'puntuacion' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}