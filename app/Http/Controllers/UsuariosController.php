<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsuariosController extends Controller
{
    public function getNombreByUserId($id_user){
        $user = User::find($id_user);
        return $user->name;
    }
}
