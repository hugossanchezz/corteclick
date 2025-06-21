<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PeluqueriaSolicitud;
use App\Models\PeluqueriaFotoTemporal;
use App\Models\PeluqueriaFoto;
use App\Models\Peluqueria;

class PeluqueriaSolicitudController extends Controller
{
    public function createLocalRequest(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:200',
                'direccion' => 'required|string|max:150',
                'localidad' => 'nullable|integer|exists:localidades,id',
                'email' => 'required|email|max:150',
                'telefono' => 'nullable|string|max:20',
                'tipo' => 'required|in:BARBERIA,PELUQUERIA,UNISEX',
                'user_id' => 'required|exists:users,id',
                'imagen' => 'required|file|mimes:jpeg,png,jpg,webp|max:10240',
                'otras_imagenes' => 'nullable|array|max:4',
                'otras_imagenes.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:10240',
            ]);

            DB::beginTransaction();

            if (!$request->hasFile('imagen')) {
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
            }

            DB::commit();

            return response()->json(['message' => 'Solicitud enviada correctamente.']);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar la solicitud.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getLocalsRequest(Request $request)
    {
        try {

            // Cargar solicitudes con fotos temporales
            $solicitudes = PeluqueriaSolicitud::with('fotosTemporales')
                ->get();

            // Transformar cada solicitud
            $solicitudesTransformadas = $solicitudes->map(function ($solicitud) {
                return [
                    'id' => $solicitud->id,
                    'estado' => $solicitud->estado,
                    'fecha' => $solicitud->fecha,
                    'nombre' => $solicitud->nombre,
                    'descripcion' => $solicitud->descripcion,
                    'direccion' => $solicitud->direccion,
                    'localidad' => $solicitud->localidad,
                    'email' => $solicitud->email,
                    'telefono' => $solicitud->telefono,
                    'tipo' => $solicitud->tipo,
                    'user_id' => $solicitud->user_id,
                    'imagen' => $solicitud->imagen ? base64_encode($solicitud->imagen) : null,
                    'fotos_temporales' => $solicitud->fotosTemporales->map(function ($foto) {
                        return [
                            'id' => $foto->id,
                            'imagen' => $foto->imagen ? base64_encode($foto->imagen) : null,
                        ];
                    }),
                ];
            });

            return response()->json($solicitudesTransformadas, 200);
        } catch (\Exception $e) {
            \Log::error("Error fetching local requests: " . $e->getMessage());
            return response()->json(['error' => 'Error al cargar las solicitudes'], 500);
        }
    }

    public function approveRequest($id)
    {
        DB::beginTransaction();

        try {
            $solicitud = PeluqueriaSolicitud::with('fotosTemporales')->findOrFail($id);

            // Crear nueva peluquería a partir de la solicitud
            $peluqueria = Peluqueria::create([
                'nombre' => $solicitud->nombre,
                'descripcion' => $solicitud->descripcion,
                'direccion' => $solicitud->direccion,
                'localidad' => $solicitud->localidad,
                'email' => $solicitud->email,
                'telefono' => $solicitud->telefono,
                'tipo' => $solicitud->tipo,
                'user_id' => $solicitud->user_id,
                'estado' => 'APROBADO',
                'imagen' => $solicitud->imagen,
            ]);

            // Mover fotos temporales a peluquerias_fotos
            foreach ($solicitud->fotosTemporales as $fotoTemporal) {
                PeluqueriaFoto::create([
                    'id_peluqueria' => $peluqueria->id,
                    'imagen' => $fotoTemporal->imagen,
                ]);
            }

            // Eliminar fotos temporales
            PeluqueriaFotoTemporal::where('id_peluqueria_solicitud', $solicitud->id)->delete();

            // Actualizar estado de la solicitud
            $solicitud->estado = 'APROBADA';
            $solicitud->save();

            DB::commit();

            return response()->json([
                'message' => 'Solicitud aprobada correctamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al aprobar la solicitud', 'detalles' => $e->getMessage()], 500);
        }
    }

    public function cambiarEstado(Request $request, $id)
    {
        $solicitud = PeluqueriaSolicitud::with('fotosTemporales')->findOrFail($id);
        $nuevoEstado = $request->input('estado');

        // Validar que el nuevo estado sea válido
        $estadosValidos = ['APROBADA', 'RECHAZADA'];
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return response()->json(['error' => 'Estado no válido'], 400);
        }

        // Eliminar fotos temporales si existen
        foreach ($solicitud->fotosTemporales as $foto) {
            $foto->delete();
        }

        // Eliminar solicitud
        $solicitud->delete();

        return response()->json([
            'message' => "Solicitud {$nuevoEstado} y eliminada correctamente."
        ], 200);
    }

}
