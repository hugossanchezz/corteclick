<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeluqueriaSolicitud extends Model
{
    use HasFactory;

    protected $table = 'peluquerias_solicitudes';

    protected $fillable = [
        'estado',
        'fecha',
        'nombre',
        'descripcion',
        'direccion',
        'localidad',
        'email',
        'telefono',
        'tipo',
        'contrasenia',
        'user_id',
    ];

    // Relaciones (opcional)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function localidadRelacionada()
    {
        return $this->belongsTo(Localidad::class, 'localidad');
    }
}
