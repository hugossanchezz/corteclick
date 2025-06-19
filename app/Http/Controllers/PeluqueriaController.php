<?php

namespace App\Http\Controllers;

use App\Models\Peluqueria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

    /**
     * Show the form for creating a new resource.
     */
    public function createNewLocal(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'direccion' => 'required|string|max:255',
                'localidad' => 'required|integer|exists:localidades,id',
                'email' => 'required|email|unique:peluquerias,email',
                'telefono' => 'required|string|max:20',
                'tipo' => 'required|string|in:PELUQUERIA,BARBERIA,UNISEX',
                'user_id' => 'required|integer|exists:users,id',
                'imagen' => 'required|string', //para guardar imagern en base64
            ]);

            // Procesar imagen base64 y convertirla a binario
            $base64Image = $validated['imagen'];

            if (str_contains($base64Image, ',')) {
                [, $content] = explode(',', $base64Image, 2);
            } else {
                $content = $base64Image; // Ya es solo la parte útil del base64
            }

            $imagenBlob = base64_decode($content);


            // Crear nueva peluquería
            $peluqueria = Peluqueria::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'direccion' => $validated['direccion'],
                'localidad' => $validated['localidad'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'],
                'tipo' => $validated['tipo'],
                'user_id' => $validated['user_id'],
                'valoracion' => null,
                'imagen' => $imagenBlob,
            ]);

            return response()->json([
                'message' => 'Peluquería creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear la peluquería: ' . $e->getMessage(),
            ], 500);
        }
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
        return response()->json($peluqueria);
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
