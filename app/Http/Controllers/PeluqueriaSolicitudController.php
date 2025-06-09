<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PeluqueriaSolicitud;
use App\Models\PeluqueriaFotoTemporal;

class PeluqueriaSolicitudController extends Controller
{
    public function createLocalRequest(Request $request)
    {
        try {
            Log::info('➡️ Iniciando validación...');
            $validated = $request->validate([
                'nombre' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:200',
                'direccion' => 'required|string|max:150',
                'localidad' => 'nullable|integer|exists:localidades,id',
                'email' => 'required|email|max:150',
                'telefono' => 'nullable|string|max:20',
                'tipo' => 'required|in:BARBERIA,PELUQUERIA,UNISEX',
                'user_id' => 'required|exists:users,id',
                'imagen' => 'required|file|mimes:jpeg,png,jpg,webp|max:10240', // 10MB
                'otras_imagenes.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:10240',
            ]);

            DB::beginTransaction();

            Log::info('✅ Validación completada', ['validated' => $validated]);

            if (!$request->hasFile('imagen')) {
                Log::error('❌ No se recibió archivo imagen');
                return response()->json(['message' => 'No se recibió la imagen principal'], 400);
            }

            $imagenBlob = file_get_contents($request->file('imagen')->getRealPath());

            $solicitud = PeluqueriaSolicitud::create([
                'estado' => 'PENDIENTE',
                'fecha' => now('Europe/Madrid'),
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? '',
                'direccion' => $validated['direccion'],
                'localidad' => $validated['localidad'] ?? null,
                'email' => $validated['email'],
                'telefono' => $validated['telefono'] ?? '',
                'tipo' => $validated['tipo'],
                'user_id' => $validated['user_id'],
                'imagen' => $imagenBlob,
            ]);

            Log::info('📝 Solicitud creada', ['id' => $solicitud->id]);

            if ($request->hasFile('otras_imagenes')) {
                foreach ($request->file('otras_imagenes') as $archivo) {
                    $bin = file_get_contents($archivo->getRealPath());
                    PeluqueriaFotoTemporal::create([
                        'id_peluqueria_solicitud' => $solicitud->id,
                        'imagen' => $bin,
                    ]);
                }
                Log::info('📸 imagenes temporales guardadas');
            }

            DB::commit();

            return response()->json(['message' => 'Solicitud enviada correctamente.']);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('🔥 Error en createLocalRequest', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error al registrar la solicitud.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getLocalsRequest(Request $request)
    {
        try {
            $estado = $request->query('estado', 'PENDIENTE');
            $solicitudes = PeluqueriaSolicitud::where('estado', $estado)->get();
            return response()->json($solicitudes, 200);
        } catch (\Exception $e) {
            \Log::error("Error fetching local requests: " . $e->getMessage());
            return response()->json(['error' => 'Error al cargar las solicitudes'], 500);
        }
    }

    public function cambiarEstado(Request $request, $id)
    {
        $solicitud = PeluqueriaSolicitud::findOrFail($id);
        $nuevoEstado = $request->input('estado');

        // Validar que el nuevo estado sea válido
        $estadosValidos = ['PENDIENTE', 'APROBADA', 'RECHAZADA'];
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return response()->json(['error' => 'Estado no válido'], 400);
        }

        // Actualizar el estado
        $solicitud->estado = $nuevoEstado;
        $solicitud->save();

        return response()->json(['message' => "Solicitud cambiada a {$nuevoEstado} exitosamente"], 200);
    }
}
