<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PeluqueriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('peluquerias')->insert([
            [
                'nombre' => 'Peluquería Rosa',
                'descripcion' => 'Peluquería unisex con amplia experiencia.',
                'direccion' => 'Calle Morales 34',
                'localidad' => 1, // Asumiendo que 1 es una localidad válida
                'email' => 'rosa@example.com',
                'telefono' => '+34 912345678',
                'tipo' => 'UNISEX',
                'contrasenia' => Hash::make('password123'), // Encripta la contraseña
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Barber Shop El Barbero',
                'descripcion' => 'Barbería especializada en cortes masculinos.',
                'direccion' => 'Avenida Principal 12',
                'localidad' => 2, // Asumiendo que 2 es una localidad válida
                'email' => 'barber@example.com',
                'telefono' => '+34 911223344',
                'tipo' => 'BARBERIA',
                'contrasenia' => Hash::make('secreto456'), // Encripta la contraseña
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'nombre' => 'Peluqueria Carmen',
                'descripcion' => 'Peluquería para señoras',
                'direccion' => 'Calle Larga 12',
                'localidad' => 3,
                'email' => 'carmen@example.com',
                'telefono' => '+34 666778899',
                'tipo' => 'PELUQUERIA',
                'contrasenia' => Hash::make('carmen123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
