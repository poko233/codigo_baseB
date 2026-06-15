<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'Rol';

    protected $fillable = [
        'rol',
        'descripcion',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'UserRol', 'id_rol', 'id_user');
    }

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'ModuloRol', 'id_rol', 'id_modulo');
    }

    /**
     * Permisos CRUD de este rol sobre cada formulario.
     * Tabla: FormularioPermiso (id_rol, id_formulario, puede_leer, puede_crear, puede_editar, puede_eliminar)
     */
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'id_rol');
    }

    /** Formularios a los que este rol tiene al menos 'puede_leer=true'. */
    public function formulariosPermitidos()
    {
        return $this->belongsToMany(
            Formulario::class,
            'FormularioPermiso',
            'id_rol',
            'id_formulario'
        )->wherePivot('puede_leer', true)->withPivot(['puede_crear', 'puede_editar', 'puede_eliminar']);
    }
}