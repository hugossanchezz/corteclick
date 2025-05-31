<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ServiciosPeluqueria extends Pivot
{
    protected $table = 'servicios_peluqueria';

    protected $fillable = [
        'id_peluqueria',
        'id_servicio',
        'precio',
        'duracion',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'duracion' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function peluqueria()
    {
        return $this->belongsTo(Peluqueria::class, 'id_peluqueria');
    }

    public function servicio()
    {
        // Esta tabla pivot pertenece a un servicio identificado por 'servicio_id'
        // Nota: el primer parámetro es el modelo, segundo es la FK local, tercero la PK del modelo Servicio
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id');
    }
}