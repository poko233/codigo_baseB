<?php

namespace App\Modules\Auth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'usuario' => $this->usuario,
            'nombres' => $this->nombres,
            'primer_apellido' => $this->primer_apellido,
            'segundo_apellido' => $this->segundo_apellido,
            'email' => $this->email,
            'estado' => $this->estado,
            'foto' => $this->foto,
            // Relaciones solo si están cargadas
            'empresas' => $this->whenLoaded('empresas'),
            'roles' => $this->whenLoaded('roles'),
        ];
    }
}