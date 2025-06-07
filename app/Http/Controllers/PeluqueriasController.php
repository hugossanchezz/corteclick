<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\Localidad;
use App\Models\Peluqueria;

class PeluqueriasController extends Controller
{
    public function getIdsByCodigoPostalONombre(string $valor): JsonResponse
    {
        try {
            if (ctype_digit($valor)) {
                // Búsqueda por código postal
                $ids = Localidad::where('codigo_postal', 'LIKE', $valor . '%')
                    ->pluck('id')
                    ->toArray();
            } else {
                $nombre = strtoupper($valor); // Para búsquedas insensibles a mayúsculas/minúsculas

                // IDs por nombre de localidad
                $idsLocalidades = Localidad::where('nombre', 'LIKE', '%' . $nombre . '%')
                    ->pluck('id')
                    ->toArray();

                // IDs de localidades a través del nombre de peluquerías
                $idsDesdePeluquerias = Peluqueria::where('nombre', 'LIKE', '%' . $nombre . '%')
                    ->pluck('localidad')
                    ->toArray();

                // Unificar y eliminar duplicados
                $ids = array_unique(array_merge($idsLocalidades, $idsDesdePeluquerias));
            }

            return response()->json(array_values($ids));
        } catch (Throwable $e) {
            Log::error("Error al obtener ID(s) con valor '{$valor}': " . $e->getMessage());
            return response()->json([], 500);
        }
    }

}
