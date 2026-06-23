<?php

namespace App\Modules\Modulo\Models;

use App\Shared\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use BelongsToEmpresa;

    protected $table = 'modulo';

    protected $fillable = [
        'id_empresa',
        'modulo',
        'descripcion',
        'icono',
        'estado',
    ];

    public function empresa()
    {
        return $this->belongsTo(\App\Modules\Empresa\Models\Empresa::class, 'id_empresa');
    }

    public function formularios()
    {
        return $this->belongsToMany(
            \App\Modules\Formulario\Models\Formulario::class,
            'formulario_modulo',
            'id_modulo',
            'id_formulario'
        );
    }

    public function roles()
    {
        return $this->belongsToMany(
            \App\Modules\Rol\Models\Rol::class,
            'modulo_rol',
            'id_modulo',
            'id_rol'
        );
    }

    public function formularioPermisos()
    {
        return $this->hasMany(
            \App\Modules\Permiso\Models\FormularioPermiso::class,
            'id_modulo'
        );
    }
}
