<?php

namespace App\Modules\Modulo\Resource;

use App\Modules\Formulario\Resource\FormularioResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuloResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'id_empresa'  => $this->id_empresa,
            'modulo'      => $this->modulo,
            'descripcion' => $this->descripcion,
            'icono'       => $this->icono,
            'estado'      => $this->estado,
            'formularios' => FormularioResource::collection($this->whenLoaded('formularios')),
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
