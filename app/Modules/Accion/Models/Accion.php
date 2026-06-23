<?php

namespace App\Modules\Accion\Models;

use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    protected $table = 'accion';

    public $timestamps = false;

    protected $fillable = ['accion'];
}
