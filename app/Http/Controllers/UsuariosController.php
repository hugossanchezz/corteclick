<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsuariosController extends Controller
{
    public function getNombreByUserId($id_user): string
    {
        $user = User::find($id_user);
        return $user->name;
    }
}
