<?php

namespace App\Modules\Roles\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RolResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'id_empresa'  => $this->id_empresa,
            'rol'         => $this->rol,
            'descripcion' => $this->descripcion,
            'estado'      => $this->estado,
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}