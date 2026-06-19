<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $guarded = ['id'];
    protected $casts = [
        'tipo_cambio' => 'decimal:2',
        'estado' => 'string',
    ];
}
