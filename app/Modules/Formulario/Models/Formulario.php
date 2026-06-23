<?php

namespace App\Modules\Formulario\Models;

use App\Shared\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    use BelongsToEmpresa;

    protected $table = 'formulario';

    protected $fillable = [
        'id_empresa',
        'formulario',
        'descripcion',
        'estado',
        'ruta',
    ];

    public function empresa()
    {
        return $this->belongsTo(\App\Modules\Empresa\Models\Empresa::class, 'id_empresa');
    }

    public function modulos()
    {
        return $this->belongsToMany(
            \App\Modules\Modulo\Models\Modulo::class,
            'formulario_modulo',
            'id_formulario',
            'id_modulo'
        );
    }

    public function permisos()
    {
        return $this->hasMany(
            \App\Modules\Permiso\Models\FormularioPermiso::class,
            'id_formulario'
        );
    }
}
