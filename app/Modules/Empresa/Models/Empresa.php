<?php

namespace App\Empresas\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $guarded = ['id'];
}