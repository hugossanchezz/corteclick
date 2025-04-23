<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PeluqueriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('peluquerias')->insert([
            [
                'nombre' => 'Peluqueria Rosa',
                'direccion' => 'Calle Morales 34',
            ],
            [
                'nombre' => 'Peluqueria Barber',
                'direccion' => 'Calle Tomillo 12',
            ]
        ]);
    }
}
