<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'Modulo';

    protected $fillable = [
        'modulo',
        'descripcion',
        'icono',
    ];

    /** Roles que tienen acceso directo a este módulo (modulo_rol). */
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'ModuloRol', 'id_modulo', 'id_rol');
    }

    /** Formularios hijos de este módulo, ordenados. */
    public function formularios()
    {
        return $this->belongsToMany(
            Formulario::class,
            'FormularioModulo',
            'id_modulo',
            'id_formulario'
        )->orderBy('id');
    }
}