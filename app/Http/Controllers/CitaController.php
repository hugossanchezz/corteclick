<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Carbon\CarbonInterface;
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
        $now = Carbon::now('Europe/Madrid');
        $weekday = $now->dayOfWeekIso; // 1 (lunes) - 7 (domingo)
        $hour = $now->hour + ($now->minute / 60);

        // Si es viernes (5) y pasan de las 14:00, ir al siguiente lunes
        if ($weekday === 5 && $hour >= 14 || $weekday > 5) {
            $startDate = $now->copy()->next(CarbonInterface::MONDAY);
        } else {
            // Ir al lunes de la semana actual
            $startDate = $now->copy()->startOfWeek(CarbonInterface::MONDAY);
        }

        $citas = Cita::where('id_peluqueria', $id_peluqueria)
            ->where('fecha', '>=', $startDate->toDateString())
            ->where('estado', '=', 'CONFIRMADA')
            ->get();

        return response()->json($citas);
    }

    /**
     * Obtener todas las citas de un usuario.
     *
     * @param int $id_usuario
     * @return JsonResponse
     */
    public function getCitasByIdUsuario(int $id_usuario): JsonResponse
    {
        $citas = Cita::where('id_usuario', $id_usuario)
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();

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

    public function deleteCita($id_cita)
    {
        $cita = Cita::find($id_cita);
        $cita->delete();
        return response()->json([
            'mensaje' => 'Cita eliminada correctamente'
        ], 200);
    }

    public function cancelCita($id_cita)
    {
        $cita = Cita::find($id_cita);
        $cita->estado = 'CANCELADA';
        $cita->save();
        return response()->json([
            'mensaje' => 'Cita cancelada correctamente'
        ], 200);
    }

    public function marcarCitasTerminadas()
    {
        $ahora = Carbon::now('Europe/Madrid');

        $actualizadas = Cita::where('estado', 'CONFIRMADA')
            ->whereRaw("CONCAT(fecha, ' ', hora_fin) < ?", [$ahora->format('Y-m-d H:i:s')])
            ->update(['estado' => 'TERMINADA']);

        return response()->json(['updated' => $actualizadas]);
    }

    public function usuarioTieneCita($id_peluqueria, $id_usuario)
    {
        // Comprobar si existe al menos una cita de ese usuario en esa peluquería
        $existeCita = Cita::where('id_peluqueria', $id_peluqueria)
            ->where('id_usuario', $id_usuario)
            ->where('estado', 'TERMINADA')
            ->exists();

        return response()->json(['tieneCita' => $existeCita]);
    }

    public function getValoracionesById($id_peluqueria)
    {
        $citas = Cita::where('id_peluqueria', $id_peluqueria)
            ->where('estado', 'TERMINADA')
            ->whereNotNull('puntuacion') // solo citas que tengan texto
            ->get(['valoracion', 'puntuacion', 'respuesta', 'fecha']);

        return response()->json($citas);
    }
}
