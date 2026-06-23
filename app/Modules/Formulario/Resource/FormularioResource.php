<?php

namespace App\Modules\Formulario\Resource;

use App\Modules\Modulo\Resource\ModuloResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FormularioResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'id_empresa'  => $this->id_empresa,
            'formulario'  => $this->formulario,
            'descripcion' => $this->descripcion,
            'ruta'        => $this->ruta,
            'estado'      => $this->estado,
            'modulos'     => ModuloResource::collection($this->whenLoaded('modulos')),
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
