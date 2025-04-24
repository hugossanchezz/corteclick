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
            ['id' => 1, 'nombre' => 'Ciudad Real', 'codigo_postal' => '13001'],
            ['id' => 2, 'nombre' => 'Toledo', 'codigo_postal' => '45001'],
            ['id' => 3, 'nombre' => 'Albacete', 'codigo_postal' => '02001'],
            ['id' => 4, 'nombre' => 'Cuenca', 'codigo_postal' => '16001'],
            ['id' => 5, 'nombre' => 'Guadalajara', 'codigo_postal' => '19001'],
        ]);
    }
}
