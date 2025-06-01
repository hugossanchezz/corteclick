<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CitaController extends Controller
{
    /**
     * Mostrar todas las citas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCitas(): JsonResponse
    {
        $citas = Cita::all();
        return response()->json($citas);
    }

    /**
     * Obtener todas las citas de una peluquería.
     *
     * @param int $id_peluqueria
     * @return JsonResponse
     */
    public function getCitasByIdPeluqueria(int $id_peluqueria): JsonResponse
    {
        $citas = Cita::where('id_peluqueria', $id_peluqueria)->get();
        return response()->json($citas);
    }

    /**
     * Crear una cita nueva.
     *
     * @bodyParam id_usuario integer required ID del usuario que solicita la cita.
     * @bodyParam id_peluqueria integer required ID de la peluquería donde se realizará la cita.
     * @bodyParam id_servicio integer required ID del servicio a realizar en la cita.
     * @bodyParam fecha date required Fecha en la que se realizará la cita.
     * @bodyParam hora_inicio time required Hora de inicio de la cita.
     * @bodyParam hora_fin time required Hora de fin de la cita.
     * @bodyParam estado string required Estado de la cita (CONFIRMADA, CANCELADA, TERMINADA).
     * @bodyParam valoracion string nullable Comentario de la valoración de la cita.
     * @bodyParam puntuacion integer nullable Puntuación de la cita (entre 1 y 5).
     * @bodyParam respuesta string nullable Respuesta de la peluquería a la valoración de la cita.
     *
     * @response 201 {
     *   "mensaje": "Cita registrada correctamente"
     * }
     */
    public function createCita(Request $request): JsonResponse
    {
        // Validación de datos
        $request->validate([
            'id_usuario' => 'required|exists:users,id',
            'id_peluqueria' => 'required|exists:peluquerias,id',
            'id_servicio' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'estado' => 'required|in:CONFIRMADA,CANCELADA,TERMINADA',
            'valoracion' => 'nullable|string|max:200',
            'puntuacion' => 'nullable|integer|min:1|max:5',
            'respuesta' => 'nullable|string|max:200',
        ]);

        // Crear la cita
        Cita::create($request->all());

        return response()->json([
            'mensaje' => 'Cita registrada correctamente'
        ], 201);
    }
}
