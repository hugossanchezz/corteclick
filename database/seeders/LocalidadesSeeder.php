<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('localidades')->insert([
            ['id' => 1, 'nombre' => 'ALBACETE', 'codigo_postal' => '02001'],
            ['id' => 2, 'nombre' => 'ALMERIA', 'codigo_postal' => '04001'],
            ['id' => 3, 'nombre' => 'CIUDAD REAL', 'codigo_postal' => '13001'],
            ['id' => 4, 'nombre' => 'CUENCA', 'codigo_postal' => '16001'],
            ['id' => 5, 'nombre' => 'GUADALAJARA', 'codigo_postal' => '19001'],
            ['id' => 6, 'nombre' => 'SEGOVIA', 'codigo_postal' => '40001'],
            ['id' => 7, 'nombre' => 'TOLEDO', 'codigo_postal' => '45001'],
        ]);
    }
}
