<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Localidad;

class Peluqueria extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'peluquerias';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'localidad',
        'email',
        'telefono',
        'tipo',
        'valoracion',
        'user_id',
        'imagen',
    ];

    /**
     * Los atributos que deberían ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'localidad' => 'integer', // Asegúrate de que localidad se castea a integer
        'tipo' => 'string',
    ];

    /**
     * Indica si el modelo debe registrar las marcas de tiempo.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Define la relación con la localidad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'localidad');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicios_peluqueria', 'id_peluqueria', 'id_servicio')
            ->using(ServiciosPeluqueria::class)
            ->withPivot('precio', 'duracion');
    }

    public function fotos()
    {
        return $this->hasMany(PeluqueriaFoto::class, 'id_peluqueria');
    }

}
