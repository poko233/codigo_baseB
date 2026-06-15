<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Empresa extends Model
{
    protected $table = 'Empresa';
 
    protected $primaryKey = 'id';
 
    public $timestamps = false;
 
    protected $fillable = [
        'empresa',
        'slogan',
        'sigla',
        'telefono',
        'celular',
        'email',
        'direccion',
        'responsable',
        'latitud',
        'longitud',
        'objeto',
        'mision',
        'vision',
        'facebook',
        'instagram',
        'tiktok',
        'linkedin',
        'carrito',
        'tipo_cambio',
        'logo_cuadrado',
        'logo_largo',
        'baner_inicio',
        'icono',
        'titulo_cierre',
        'mensaje_cierre',
        'titulo_inicio',
        'mensaje_inicio',
        'dominio',
        'smtp_correo',
        'correo_institucional',
        'pwd_institucional',
    ];
 
    /**
     * Campos sensibles excluidos de la serialización JSON.
     * Nunca expongas contraseñas en respuestas de API.
     */
    protected $hidden = [
        'pwd_institucional',
    ];
 
    protected $casts = [
        'tipo_cambio' => 'float',
    ];
}