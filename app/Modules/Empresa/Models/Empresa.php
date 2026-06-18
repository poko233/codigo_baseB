<?php

namespace App\Empresas\Models;

use App\Modules\Auth\Models\User;
use App\Modules\Roles\Models\Rol;
use App\Modules\Modulos\Models\Modulo;
use App\Modules\Formularios\Models\Formulario;
use App\Modules\Sucursales\Models\Sucursal;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $guarded = ['id'];

    protected $casts = [
        'tipo_cambio' => 'decimal:2',
        'estado'      => 'string',
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