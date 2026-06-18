<?php

namespace App\Modules\Roles\Models;

use App\Modules\Shared\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use BelongsToEmpresa;

    protected $table = 'roles';
    protected $guarded = ['id'];
}