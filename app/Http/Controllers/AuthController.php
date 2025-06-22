<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|string|min:8',
            'apellidos' => 'nullable|string|max:100',
            'telefono' => 'nullable|digits:9|unique:users',
            'localidad' => 'nullable|integer|max:20',
            'aceptarTerminos' => 'required|accepted',
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
            'rol_id' => 3, // Rol de usuario por defecto
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

    public function updateUser(Request $request, $id)
    {
        // Verifica que el usuario autenticado coincide con el ID proporcionado
        if (Auth::id() != $id) {
            return response()->json([
                'message' => 'No tienes permiso para actualizar este usuario.',
            ], 403);
        }

        // Valida los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'apellidos' => 'required|string|max:200',
            'telefono' => 'required|digits:9',
            'localidad' => 'required|exists:localidades,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Busca el usuario por ID
            $user = User::findOrFail($id);

            // Actualiza los datos del usuario
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'localidad' => $request->localidad,
            ]);

            return response()->json([
                'message' => 'Usuario actualizado correctamente.',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el usuario.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
