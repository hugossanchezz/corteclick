<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Peluqueria;
use App\Models\PeluqueriaFoto;
use App\Models\PeluqueriaFotoTemporal;

class PeluqueriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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


    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $peluquerias = Peluqueria::where('nombre', 'like', '%' . $query . '%')->get();
        return response()->json($peluquerias);
    }

    public function deleteLocalByEmail($email)
    {
        try {
            // Buscar la peluquería por email
            $peluqueria = Peluqueria::where('email', $email)->first();

            // Verificar si la peluquería existe
            if (!$peluqueria) {
                return response()->json([
                    'error' => 'Peluquería no encontrada con el email proporcionado.',
                ], 404);
            }

            // Eliminar la peluquería
            $peluqueria->delete();

            return response()->json([
                'message' => 'Peluquería eliminada exitosamente.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la peluquería: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getPeluqueriaById($id)
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
            'user_id' => $peluqueria->user_id,
            'estado' => $peluqueria->estado,
            'imagen' => $peluqueria->imagen ? base64_encode($peluqueria->imagen) : null,
            // agrega más campos si tu modelo tiene más
        ]);
    }


    public function getPeluqueriaNombreById($id)
    {
        $peluqueria = Peluqueria::find($id);
        if (!$peluqueria) {
            return response('Peluquería no encontrada', 404);
        }

        return response($peluqueria->nombre, 200)
            ->header('Content-Type', 'text/plain');
    }

}
