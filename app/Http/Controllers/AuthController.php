<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|string|min:8',
            'apellidos' => 'nullable|string|max:100', // Cambiado a nullable
            'telefono' => 'nullable|string|max:20',    // Cambiado a nullable
            'localidad' => 'nullable|integer|max:20',  // Cambiado a nullable
            'aceptarTerminos' => 'required|accepted', // Agregada validación para los términos
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Devuelve un array 'errors'
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'localidad' => $request->localidad,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200); //Devolver 200 en caso de éxito
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciales incorrectas.'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Hola ' . $user->name,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => auth()->user(), // Usa auth()->user() para obtener el usuario autenticado.
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([ // Devuelve la respuesta como JSON
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}
