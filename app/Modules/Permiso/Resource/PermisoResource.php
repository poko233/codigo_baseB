<?php

namespace App\Modules\Permiso\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class PermisoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rol' => $this->whenLoaded('rol', function () {
                return [
                    'id' => $this->rol->id,
                    'rol' => $this->rol->rol,
                ];
            }),
            'modulo' => $this->whenLoaded('modulo', function () {
                return [
                    'id' => $this->modulo->id,
                    'modulo' => $this->modulo->modulo,
                ];
            }),
            'formulario' => $this->whenLoaded('formulario', function () {
                return [
                    'id' => $this->formulario->id,
                    'formulario' => $this->formulario->formulario,
                ];
            }),
            'accion' => $this->whenLoaded('accion', function () {
                return [
                    'id' => $this->accion->id,
                    'accion' => $this->accion->accion,
                ];
            }),
            'created_at' => $this->created_at,
        ];
    }
}