<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LocalidadesSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(PeluqueriasSeeder::class);
        $this->call(ServiciosSeeder::class);
        $this->call(ServiciosPeluqueriaSeeder::class);
        $this->call(CitasSeeder::class);
    }
}
