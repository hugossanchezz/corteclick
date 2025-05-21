<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'apellidos' => 'Administrador',
                'telefono' => '600000001',
                'localidad' => rand(1, 7),
                'foto' => null,
                'rol_id' => 1,
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin123@'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Usuario',
                'apellidos' => 'López',
                'telefono' => '600000003',
                'localidad' => rand(1, 7),
                'foto' => null,
                'rol_id' => 2,
                'email' => 'usuario@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Usuario123@'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}