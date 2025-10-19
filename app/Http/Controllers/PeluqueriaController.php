<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;
use App\Models\Peluqueria;
use App\Models\Localidad;

class PeluqueriaController extends Controller
{
    public function getPeluquerias(): JsonResponse
    {
        $peluquerias = Peluqueria::all()->map(function ($p) {
            return [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'descripcion' => $p->descripcion,
                'direccion' => $p->direccion,
                'localidad' => $p->localidad,
                'email' => $p->email,
                'telefono' => $p->telefono,
                'tipo' => $p->tipo,
                'valoracion' => $p->valoracion,
                'user_id' => $p->user_id,
                'created_at' => $p->created_at,
                'updated_at' => $p->updated_at,
                'imagen' => $p->imagen ? 'data:image/jpeg;base64,' . base64_encode($p->imagen) : null,
            ];
        });

        return response()->json($peluquerias);
    }

    public function searchPeluquerias(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $peluquerias = Peluqueria::where('nombre', 'like', '%' . $query . '%')->get();
        return response()->json($peluquerias);
    }

    public function getPeluqueriaById($id): JsonResponse
    {
        $peluqueria = Peluqueria::find($id);

        if (!$peluqueria) {
            return response()->json(['error' => 'Peluquería no encontrada'], 404);
        }

        return response()->json([
            'id' => $peluqueria->id,
            'nombre' => $peluqueria->nombre,
            'descripcion' => $peluqueria->descripcion,
            'direccion' => $peluqueria->direccion,
            'localidad' => $peluqueria->localidad,
            'email' => $peluqueria->email,
            'telefono' => $peluqueria->telefono,
            'tipo' => $peluqueria->tipo,
            'valoracion' => $peluqueria->valoracion,
            'user_id' => $peluqueria->user_id,
            'imagen' => $peluqueria->imagen ? base64_encode($peluqueria->imagen) : null,
        ]);
    }

    public function getPeluqueriaNombreById($id): Response
    {
        $peluqueria = Peluqueria::find($id);
        if (!$peluqueria) {
            return response('Peluquería no encontrada', 404);
        }

        return response($peluqueria->nombre, 200)
            ->header('Content-Type', 'text/plain');
    }

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
            return response()->json([], 500);
        }
    }

    public function getPeluqueriasByUserId($user_id): JsonResponse
    {
        $peluquerias = Peluqueria::where('user_id', $user_id)->get();

        $datos = $peluquerias->map(function ($peluqueria) {
            return [
                'id' => $peluqueria->id,
                'nombre' => $peluqueria->nombre,
                'descripcion' => $peluqueria->descripcion,
                'direccion' => $peluqueria->direccion,
                'localidad' => $peluqueria->localidad,
                'email' => $peluqueria->email,
                'telefono' => $peluqueria->telefono,
                'tipo' => $peluqueria->tipo,
                'user_id' => $peluqueria->user_id,
                'estado' => $peluqueria->estado,
                'imagen' => $peluqueria->imagen ? base64_encode($peluqueria->imagen) : null,
            ];
        });

        return response()->json($datos);
    }
}
