<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RolesSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'nombre' => 'administrador',
                'descripcion' => 'Rol con todos los privilegios.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'empresario',
                'descripcion' => 'Usuario propietario de una peluquería.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'usuario',
                'descripcion' => 'Rol de usuario básico.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}