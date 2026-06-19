<?php

namespace App\Modules\Auth\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'user';

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

    // ✅ Carpeta real: app/Modules/Empresa → App\Modules\Empresa
    public function empresa()
    {
        return $this->belongsToMany(
            \App\Modules\Empresa\Models\Empresa::class,
            'user_empresa',
            'id_user',
            'id_empresa'
        );
    }

    // ✅ Carpeta real: app/Modules/Rol → App\Modules\Rol
    public function rol()
    {
        return $this->belongsToMany(
            \App\Modules\Rol\Models\Rol::class,
            'user_rol',
            'id_user',
            'id_rol'
        );
    }
}
