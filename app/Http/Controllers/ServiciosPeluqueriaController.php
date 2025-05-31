<?php

namespace App\Http\Controllers;

use App\Models\ServiciosPeluqueria;
use Illuminate\Http\JsonResponse;

class ServiciosPeluqueriaController extends Controller
{
    /**
     * Retrieve all services for a specific hairdresser by ID.
     *
     * @param int $peluqueria_id
     * @return JsonResponse
     */
    public function getServiciosByPeluqueriaId($peluqueria_id): JsonResponse
    {
        // 1. Obtiene todos los registros de la tabla 'servicios_peluqueria' que
        //    tengan el campo 'id_peluqueria' igual al valor recibido en $peluqueria_id.
        //    Además, con 'with('servicio')' carga en la misma consulta la relación con
        //    la tabla 'servicios', para traer los datos relacionados (como el nombre).
        $registros = ServiciosPeluqueria::with('servicio')
            ->where('id_peluqueria', $peluqueria_id)
            ->get();

        // 2. Mapea (transforma) la colección obtenida para devolver solo los campos que quieres
        //    en la respuesta JSON:
        //    - 'id' es el id del servicio (id_servicio)
        //    - 'nombre' viene de la relación con 'servicio'. Si no existe el nombre, pone 'Sin nombre'
        //    - 'precio' y 'duracion' vienen de la tabla pivot 'servicios_peluqueria'.
        $resultado = $registros->map(function ($item) {
            return [
                'id' => $item->id_servicio,
                'nombre' => $item->servicio->nombre ?? 'Sin nombre',
                'precio' => $item->precio,
                'duracion' => $item->duracion,
            ];
        });

        return response()->json($resultado);
    }

}
