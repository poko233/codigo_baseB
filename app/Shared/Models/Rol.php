<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'rol';
    protected $guarded = ['id'];
    protected $casts = [
        'estado' => 'string',
    ];
}
