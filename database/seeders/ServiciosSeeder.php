<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiciosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('servicios')->insert([
            // Servicios para hombres
            [
                'id' => 1,
                'nombre' => 'Corte de pelo clásico',
                'descripcion' => 'Corte de cabello tradicional para hombre.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Corte + arreglo de barba',
                'descripcion' => 'Combo de corte masculino y perfilado de barba.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'Fade + diseño de cejas',
                'descripcion' => 'Degradado moderno más depilación masculina de cejas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'Afeitado con toalla caliente',
                'descripcion' => 'Afeitado tradicional con navaja y tratamiento térmico.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'nombre' => 'Tinte para canas',
                'descripcion' => 'Cobertura natural de canas para cabello masculino.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'nombre' => 'Tratamiento anticaspa',
                'descripcion' => 'Tratamiento especializado para eliminar la caspa.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'nombre' => 'Masaje facial + barba',
                'descripcion' => 'Relajación facial y mantenimiento de barba.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'nombre' => 'Limpieza facial',
                'descripcion' => 'Limpieza profunda del rostro para hombres.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'nombre' => 'Diseño completo de barba',
                'descripcion' => 'Recorte, línea y forma personalizada.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'nombre' => 'Corte express',
                'descripcion' => 'Corte rápido sin lavado ni peinado.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Servicios para mujeres
            [
                'id' => 11,
                'nombre' => 'Corte y peinado',
                'descripcion' => 'Corte de puntas y peinado final.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'nombre' => 'Coloración + peinado',
                'descripcion' => 'Tinte completo seguido de peinado.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 13,
                'nombre' => 'Ondas naturales',
                'descripcion' => 'Moldeado de cabello con ondas suaves.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 14,
                'nombre' => 'Mechas balayage',
                'descripcion' => 'Técnica de coloración suave y degradada.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 15,
                'nombre' => 'Tratamiento hidratante',
                'descripcion' => 'Mascarilla profunda para cabello seco o dañado.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 16,
                'nombre' => 'Recogido de fiesta',
                'descripcion' => 'Peinado elegante para eventos especiales.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 17,
                'nombre' => 'Extensiones de cabello',
                'descripcion' => 'Colocación de extensiones naturales o sintéticas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 18,
                'nombre' => 'Alisado con keratina',
                'descripcion' => 'Alisado semi-permanente con productos nutritivos.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 19,
                'nombre' => 'Plancha + sérum',
                'descripcion' => 'Alisado con plancha y acabado con sérum hidratante.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 20,
                'nombre' => 'Brushing con volumen',
                'descripcion' => 'Cepillado con secador para lograr volumen natural.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Servicios unisex
            [
                'id' => 21,
                'nombre' => 'Lavado + masaje capilar',
                'descripcion' => 'Limpieza capilar con masaje relajante.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 22,
                'nombre' => 'Tratamiento fortalecedor',
                'descripcion' => 'Aplicación para fortalecer y evitar la caída del cabello.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 23,
                'nombre' => 'Corte infantil',
                'descripcion' => 'Corte unisex para niños y niñas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 24,
                'nombre' => 'Mascarilla capilar unisex',
                'descripcion' => 'Mascarilla nutritiva para todo tipo de cabello.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 25,
                'nombre' => 'Secado con estilo',
                'descripcion' => 'Secado con brushing o moldeado personalizado.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
