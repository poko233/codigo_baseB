<?php

namespace App\Modules\Empresa\Models;

use App\Modules\Auth\Models\User;
use App\Modules\Rol\Models\Rol;
use App\Modules\Modulo\Models\Modulo;
use App\Modules\Formulario\Models\Formulario;
use App\Modules\Sucursal\Models\Sucursal;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $guarded = ['id'];

    protected $casts = [
        'tipo_cambio' => 'decimal:2',
        'estado' => 'string',
    ];


    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_empresa',
            'id_empresa',
            'id_user'
        );
    }

    public function roles()
    {
        return $this->hasMany(Rol::class, 'id_empresa');
    }

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class, 'id_empresa');
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'id_empresa');
    }

    public function formularios()
    {
        return $this->hasMany(Formulario::class, 'id_empresa');
    }
}