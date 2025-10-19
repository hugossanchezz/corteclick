<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\JsonResponse;

class ServicioController extends Controller
{
    public function getServicios(): JsonResponse
    {
        $servicios = Servicio::all();
        return response()->json($servicios);
    }

    /**
     * Obtener nombre del servicio por id
     *
     * @param int $id_servicio
     * @return JsonResponse
     */
    public function getNombrePorId($id_servicio): JsonResponse
    {
        $servicio = Servicio::find($id_servicio);

        if (!$servicio) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }

        return response()->json(['nombre' => $servicio->nombre]);
    }
}
