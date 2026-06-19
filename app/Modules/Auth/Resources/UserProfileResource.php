<?php

namespace App\Modules\Auth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            // Identidad
            'id' => $this->id,
            'usuario' => $this->usuario,
            'nombres' => $this->nombres,
            'primer_apellido' => $this->primer_apellido,
            'segundo_apellido' => $this->segundo_apellido,
            'ci' => $this->ci,
            'expedido' => $this->expedido,

            // Contacto
            'email' => $this->email,
            'telefono' => $this->telefono,
            'celular' => $this->celular,
            'direccion' => $this->direccion,

            // Datos personales
            'genero' => $this->genero,
            'fecha_nac' => $this->fecha_nac,
            'foto' => $this->foto,

            // QR (se envía tal cual, en base64)
            'codigo_qr' => $this->codigo_qr,

            // Relaciones (solo cuando están cargadas)
            'empresas' => $this->whenLoaded('empresas'),
            'roles' => $this->whenLoaded('roles'),
            'sucursales' => $this->whenLoaded('sucursales'),
        ];
    }
}