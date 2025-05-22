<?php

namespace App\Http\Controllers;

use App\Models\PeluqueriaSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PeluqueriaSolicitudController extends Controller
{
    public function createLocalRequest(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
            'direccion' => 'required|string|max:150',
            'localidad' => 'nullable|integer|exists:localidades,id',
            'email' => 'required|email|max:150',
            'telefono' => 'nullable|string|max:20',
            'tipo' => 'required|in:BARBERIA,PELUQUERIA,UNISEX',
            'contrasenia' => 'required|string|min:8',
            'user_id' => 'required|exists:users,id',
        ]);

        PeluqueriaSolicitud::create([
            'estado' => 'PENDIENTE',
            'fecha' => now('Europe/Madrid'),
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? '',
            'direccion' => $validated['direccion'],
            'localidad' => $validated['localidad'] ?? null,
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? '',
            'tipo' => $validated['tipo'],
            'contrasenia' => Hash::make($validated['contrasenia']),
            'user_id' => $validated['user_id'],
        ]);

        return response()->json(['message' => 'Solicitud enviada correctamente.']);
    }
}
