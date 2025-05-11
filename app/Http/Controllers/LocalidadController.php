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
     * Obtiene el ID de una localidad por su código postal o nombre.
     *
     * @param string $valor El código postal o el nombre de la localidad.
     * @return int El ID de la localidad, o 0 si no se encuentra.
     */
    public function getCodigoPostalNombreById(string $valor): int
    {
        try {
            // Determina si el valor es un número entero.
            if (ctype_digit($valor)) {
                // Si es un número, busca por código postal.
                $localidad = Localidad::where('codigo_postal', $valor)->firstOrFail();
            } else {
                // Si no es un número, asume que es un nombre y busca por nombre (en mayúsculas).
                $nombre = strtoupper($valor);
                $localidad = Localidad::where('nombre', $nombre)->firstOrFail();
            }

            // Devuelve el ID de la localidad.
            return $localidad->id;
        } catch (ModelNotFoundException $e) {
            // Captura la excepción si no se encuentra la localidad.
            Log::error("Localidad no encontrada con valor '{$valor}': " . $e->getMessage());
            return 0; // Devuelve 0 para indicar que no se encontró la localidad
        } catch (Throwable $e) {
            // Captura cualquier otro error.
            Log::error("Error al obtener la localidad con valor '{$valor}': " . $e->getMessage());
            return 0; // Devuelve 0 para indicar un error
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
}