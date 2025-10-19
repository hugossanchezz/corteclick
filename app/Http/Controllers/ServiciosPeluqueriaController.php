<?php

namespace App\Http\Controllers;

use App\Models\ServiciosPeluqueria;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Pail\ValueObjects\Origin\Console;

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
        $registros = ServiciosPeluqueria::with('servicio')
            ->where('id_peluqueria', $peluqueria_id)
            ->get();

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

    public function createServicioParaPeluqueria(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_peluqueria' => 'required|exists:peluquerias,id',
            'id_servicio' => 'required|exists:servicios,id',
            'precio' => 'required|numeric|min:1',
            'duracion' => 'required|integer|min:30',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $nuevo = ServiciosPeluqueria::create([
                'id_peluqueria' => $request->id_peluqueria,
                'id_servicio' => $request->id_servicio,
                'precio' => $request->precio,
                'duracion' => $request->duracion,
            ]);

            return response()->json([
                'message' => 'Servicio añadido correctamente.',
                'servicio' => $nuevo
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al crear el servicio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteServicioPeluqueria($id_peluqueria, $id_servicio): JsonResponse
    {
        $deleted = ServiciosPeluqueria::where('id_peluqueria', $id_peluqueria)
            ->where('id_servicio', $id_servicio)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Servicio eliminado correctamente.']);
        } else {
            return response()->json(['message' => 'Servicio no encontrado.'], 404);
        }
    }
}
