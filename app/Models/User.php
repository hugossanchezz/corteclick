<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable

{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * El nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'apellidos',
        'telefono',
        'localidad',
        'foto',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deberían ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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
    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class, 'localidad');
    }
}
