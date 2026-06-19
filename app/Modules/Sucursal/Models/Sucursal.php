<?php

namespace App\Modules\Sucursal\Models;

use App\Shared\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use BelongsToEmpresa;      // ← Filtro automático por empresa

    protected $table = 'sucursal';   // ← nombre real de la tabla

    protected $guarded = ['id'];

    protected $casts = [
        'estado' => 'string',
    ];

    // Relaciones básicas (añade las que necesites)
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
            'user_sucursal',
            'id_sucursal',
            'id_user'
        );
    }
}