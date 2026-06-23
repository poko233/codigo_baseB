<?php

namespace App\Modules\Permiso\Models;

use Illuminate\Database\Eloquent\Model;

class FormularioPermiso extends Model
{
    protected $table = 'formulario_permiso';
    
    protected $fillable = [
        'id_rol',
        'id_modulo',
        'id_formulario',
        'id_accion'
    ];

    public function rol()
    {
        return $this->belongsTo(\App\Modules\Rol\Models\Rol::class, 'id_rol');
    }

    public function modulo()
    {
        return $this->belongsTo(\App\Modules\Modulo\Models\Modulo::class, 'id_modulo');
    }

    public function formulario()
    {
        return $this->belongsTo(\App\Modules\Formulario\Models\Formulario::class, 'id_formulario');
    }

    public function accion()
    {
        return $this->belongsTo(\App\Modules\Accion\Models\Accion::class, 'id_accion');
    }

    // Scope para filtrar por empresa
    public function scopeByEmpresa($query, int $idEmpresa)
    {
        return $query->whereHas('modulo', function ($q) use ($idEmpresa) {
            $q->where('id_empresa', $idEmpresa);
        })->whereHas('formulario', function ($q) use ($idEmpresa) {
            $q->where('id_empresa', $idEmpresa);
        });
    }
}