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
        $peluquerias = Peluqueria::all();
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
            // Validar los datos recibidos
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'direccion' => 'required|string|max:255',
                'localidad' => 'required|integer|exists:localidades,id',
                'email' => 'required|email|unique:peluquerias,email',
                'telefono' => 'required|string|max:20',
                'tipo' => 'required|string|in:PELUQUERIA,BARBERIA,UNISEX',
                'contrasenia' => 'required|string',
                'user_id' => 'required|integer|exists:users,id',
            ]);

            // Crear un nuevo registro en la tabla peluquerias
            $peluqueria = Peluqueria::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'direccion' => $validated['direccion'],
                'localidad' => $validated['localidad'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'],
                'tipo' => $validated['tipo'],
                'contrasenia' => $validated['contrasenia'], // Podrías encriptar la contraseña aquí si es necesario
                'user_id' => $validated['user_id'],
                'valoracion' => null, 
            ]);

            return response()->json([
                'message' => 'Peluquería creada exitosamente',
                'peluqueria' => $peluqueria,
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
}
