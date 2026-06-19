<?php

namespace App\Modules\Auth\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'user';    // ← tabla en singular

    protected $fillable = [
        'usuario',
        'password',
        'ci',
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'genero',
        'fecha_nac',
        'email',
        'telefono',
        'celular',
        'direccion',
        'expedido',
        'foto',
        'estado',
        'codigo_qr',
        'verificacion',
    ];

    protected $hidden = ['password'];

    public function empresas()
    {
        return $this->belongsToMany(
            \App\Modules\Empresa\Models\Empresa::class,
            'user_empresa',
            'id_user',
            'id_empresa'
        );
    }

    public function roles()
    {
        return $this->belongsToMany(
            \App\Modules\Rol\Models\Rol::class,
            'user_rol',
            'id_user',
            'id_rol'
        );
    }

    public function sucursales()
    {
        return $this->belongsToMany(
            \App\Modules\Sucursal\Models\Sucursal::class, // Asegúrate de tener este modelo
            'user_sucursal',
            'id_user',
            'id_sucursal'
        );
    }
}