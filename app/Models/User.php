<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'User';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'usuario',
        'password',
        'ci',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'genero',
        'fecha_nac',
        'email',
        'telefono',
        'celular',
        'direccion',
        'expedido',
        'codigo_qr',
        'verificacion',
        'foto',
        'estado',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relación muchos a muchos con roles (tabla UserRol)
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'UserRol', 'id_user', 'id_rol');
    }



    public function hasRole(string $rolNombre): bool
    {
        return $this->roles()->where('rol', $rolNombre)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('rol', $roles)->exists();
    }
}