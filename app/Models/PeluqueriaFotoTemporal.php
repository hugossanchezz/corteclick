<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeluqueriaFotoTemporal extends Model
{
    use HasFactory;

    protected $table = 'peluquerias_fotos_temporales';

    protected $fillable = [
        'id_peluqueria_solicitud',
        'imagen',
    ];

    /**
     * Relación: cada foto temporal pertenece a una solicitud de peluquería.
     */
    public function solicitud()
    {
        return $this->belongsTo(PeluqueriaSolicitud::class, 'id_peluqueria_solicitud');
    }
}
