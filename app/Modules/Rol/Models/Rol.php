<?php

namespace App\Modules\Roles\Models;

use App\Modules\Shared\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use BelongsToEmpresa;

    protected $table = 'roles';
    protected $guarded = ['id'];

    protected $casts = [
        'estado' => 'string',
    ];

    public function empresa()
    {
        return $this->belongsTo(
            \App\Modules\Empresa\Models\Empresa::class,
            'id_empresa' 
        );
    }
    public function users()
    {
        return $this->belongsToMany(
            \App\Modules\Auth\Models\User::class,
            'user_rol',   
            'id_rol',     
            'id_user'     
        );
    }
    public function modulos()
    {
        return $this->belongsToMany(
            \App\Modules\Modulos\Models\Modulo::class,
            'modulo_rol',  
            'id_rol',
            'id_modulo'
        );
    }
    public function permisos()
    {
        return $this->hasMany(
            \App\Modules\Permisos\Models\FormularioPermiso::class,
            'id_rol'
        );
    }
}