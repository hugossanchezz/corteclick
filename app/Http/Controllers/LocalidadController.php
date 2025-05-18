<?php

namespace App\Http\Controllers;

use App\Models\Localidad;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LocalidadController extends Controller
{
    /**
     * Devuelve los IDs de las localidades que coincidan con un código postal.
     * Si el valor no es un código postal, busca por nombre y devuelve el ID único.
     *
     * @param string $valor
     * @return array|int Lista de IDs si es código postal, o único ID si es nombre.
     */
    public function getIdsByCodigoPostalONombre(string $valor): JsonResponse
    {
        try {
            if (ctype_digit($valor)) {
                // Búsqueda por código postal que empiece por el valor (LIKE 'valor%')
                $ids = Localidad::where('codigo_postal', 'LIKE', $valor . '%')
                    ->pluck('id')
                    ->toArray();
            } else {
                // Búsqueda parcial por nombre (LIKE %valor%)
                $nombre = strtoupper($valor); // Pasar a mayúsculas
                $ids = Localidad::where('nombre', 'LIKE', '%' . $nombre . '%')
                    ->pluck('id')
                    ->toArray();
            }

            return response()->json($ids);
        } catch (Throwable $e) {
            Log::error("Error al obtener ID(s) con valor '{$valor}': " . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Obtiene todos los registros de la tabla localidades,
     * devolviendo solo los campos id y nombre como objetos.
     *
     * @return \Illuminate\Support\Collection Un array de objetos, donde cada objeto
     * tiene las propiedades 'id' y 'nombre'.
     */
    public function getLocalidadesIdNombre()
    {
        // Utiliza el método select() para especificar qué columnas quieres recuperar.
        $localidades = Localidad::select('id', 'nombre')->get();

        return $localidades;
    }

    /**
     * Obtiene el nombre de una localidad por su ID.
     *
     * @param int $id
     * @return string|null El nombre de la localidad, o nulo si no se encuentra.
     */
    public function getNombreById($id)
    {
        try {
            $localidad = Localidad::findOrFail($id);
            return $localidad->nombre;
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}