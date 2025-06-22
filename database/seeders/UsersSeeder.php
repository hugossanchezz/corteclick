<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'apellidos' => 'Administrador',
                'telefono' => $faker->regexify('[6-9]{1}[0-9]{8}'),
                'localidad' => rand(1, 7),
                'rol_id' => 1,
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin123@'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Ana',
                'apellidos' => 'López',
                'telefono' => $faker->regexify('[6-9]{1}[0-9]{8}'),
                'localidad' => rand(1, 7),
                'rol_id' => 2,
                'email' => 'ana.admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Usuario123@'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => $faker->firstName,
                'apellidos' => $faker->lastName,
                'telefono' => $faker->regexify('[6-9]{1}[0-9]{8}'),
                'localidad' => rand(1, 7),
                'rol_id' => 3,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('Password123@'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => $faker->firstName,
                'apellidos' => $faker->lastName,
                'telefono' => $faker->regexify('[6-9]{1}[0-9]{8}'),
                'localidad' => rand(1, 7),
                'rol_id' => 3,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('Password123@'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => $faker->firstName,
                'apellidos' => $faker->lastName,
                'telefono' => $faker->regexify('[6-9]{1}[0-9]{8}'),
                'localidad' => rand(1, 7),
                'rol_id' => 3,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('Password123@'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => $faker->firstName,
                'apellidos' => $faker->lastName,
                'telefono' => $faker->regexify('[6-9]{1}[0-9]{8}'),
                'localidad' => rand(1, 7),
                'rol_id' => 3,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('Password123@'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => $faker->firstName,
                'apellidos' => $faker->lastName,
                'telefono' => $faker->regexify('[6-9]{1}[0-9]{8}'),
                'localidad' => rand(1, 7),
                'rol_id' => 3,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('Password123@'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}