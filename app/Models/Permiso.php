<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tabla: permiso
 * Fuente de verdad de los permisos CRUD por Rol+Formulario.
 *
 * Lógica del sidebar:
 *   Un módulo aparece si el rol del usuario tiene ver=true
 *   en al menos un formulario de ese módulo.
 */
class Permiso extends Model
{
    protected $table = 'FormularioPermiso';

    protected $fillable = [
        'id_rol',
        'id_modulo',
        'id_formulario',
        'puede_crear',
        'puede_leer',
        'puede_editar',
        'puede_eliminar',
    ];

    protected $casts = [
        'puede_crear'    => 'boolean',
        'puede_leer'     => 'boolean',
        'puede_editar'   => 'boolean',
        'puede_eliminar' => 'boolean',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'id_modulo');
    }

    public function formulario()
    {
        return $this->belongsTo(Formulario::class, 'id_formulario');
    }
}
