<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeluqueriaFoto extends Model
{
    use HasFactory;

    protected $table = 'peluquerias_fotos';

    protected $fillable = [
        'id_peluqueria',
        'imagen',
    ];

    /**
     * Relación con la peluquería.
     */
    public function peluqueria()
    {
        return $this->belongsTo(Peluqueria::class, 'id_peluqueria');
    }
}
