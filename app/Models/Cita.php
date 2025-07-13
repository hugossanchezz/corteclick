<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'id_usuario',
        'id_peluqueria',
        'id_servicio',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'valoracion',
        'puntuacion',
    ];

    // Relaciones

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function peluqueria()
    {
        return $this->belongsTo(Peluqueria::class, 'id_peluqueria');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }
}
