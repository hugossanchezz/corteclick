<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeluqueriaFoto;

class PeluqueriaFotoController extends Controller
{
    public function getFotosByPeluqueria($peluqueriaId)
    {
        try {
            $fotos = PeluqueriaFoto::where('id_peluqueria', $peluqueriaId)->get();

            $imagenes = $fotos->map(function ($foto) {
                return [
                    'id' => $foto->id,
                    'imagen' => $foto->imagen ? base64_encode($foto->imagen) : null,
                ];
            });

            return response()->json($imagenes, 200);
        } catch (\Exception $e) {
            \Log::error("Error al obtener imágenes de peluquería {$peluqueriaId}: " . $e->getMessage());
            return response()->json([
                'error' => 'No se pudieron obtener las imágenes',
                'detalles' => $e->getMessage(),
            ], 500);
        }
    }

}
