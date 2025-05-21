<?php

namespace App\Http\Controllers;

use App\Models\Peluqueria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PeluqueriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
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

    public function createLocalRequest(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
            'direccion' => 'required|string|max:150',
            'localidad' => 'nullable|integer|exists:localidades,id',
            'email' => 'required|email|max:150',
            'telefono' => 'nullable|string|max:20',
            'tipo' => 'required|in:hombres,mujeres,unisex',
            'contrasenia' => 'required|string|min:8',
            'id' => 'required|exists:users,id',
        ]);

        Peluqueria::create([
            'estado' => 'PENDIENTE',
            'fecha' => now()->toDateString(),
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? '',
            'direccion' => $validated['direccion'],
            'localidad' => $validated['localidad'] ?? null,
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? '',
            'tipo' => $validated['tipo'],
            'contrasenia' => Hash::make($validated['contrasenia']),
            'user_id' => $validated['id'],
        ]);

        return response()->json(['message' => 'Solicitud enviada correctamente.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Peluqueria $peluqueria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peluqueria $peluqueria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peluqueria $peluqueria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peluqueria $peluqueria)
    {
        //
    }
}
