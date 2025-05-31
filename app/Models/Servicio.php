<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function peluquerias()
{
    // Define una relación muchos a muchos entre Servicio y Peluqueria
    return $this->belongsToMany(
        Peluqueria::class,          
        'servicios_peluqueria',    // Nombre de la tabla pivot que relaciona ambos modelos
        'id_servicio',   // Foreign key que apunta a este modelo (Servicio) en la tabla pivot
        'id_peluqueria'  // Foreign key que apunta al modelo relacionado (Peluqueria) en la tabla pivot
    )
    // Indica que la tabla pivot está representada por el modelo ServiciosPeluqueria
    ->using(ServiciosPeluqueria::class)
    // Indica que además de las claves foráneas, queremos acceder a los campos extra de la tabla pivot
    ->withPivot('precio', 'duracion'); // Campos adicionales que están en la tabla pivot
}

}