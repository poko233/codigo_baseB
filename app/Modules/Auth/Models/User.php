<?php

namespace App\Modules\Auth\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

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
    ];

    protected $hidden = ['password', 'codigo_qr', 'verificacion'];

    /**
     * Empresas a las que pertenece el usuario.
     */
    public function empresas()
    {
        return $this->belongsToMany(
            \App\Modules\Empresa\Models\Empresa::class,  // corregido
            'user_empresa',
            'id_user',
            'id_empresa'
        );
    }

    /**
     * Roles asignados al usuario (sin filtro de empresa; el servicio filtra por empresa).
     */
    public function roles()
    {
        return $this->belongsToMany(
            \App\Modules\Rol\Models\Rol::class,          // corregido
            'user_rol',
            'id_user',
            'id_rol'
        );
    }
}