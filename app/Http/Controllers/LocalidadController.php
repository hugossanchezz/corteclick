<?php

namespace App\Http\Controllers;

use App\Models\Localidad;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LocalidadController extends Controller
{
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
        $localidades = Localidad::select('id', 'nombre', 'codigo_postal')->get();

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