<?php

namespace App\Modules\Rol\Resources;

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

            
            'permisos' => $this->when(
                $this->relationLoaded('permisos'),
                fn() => $this->permisos
                    ->groupBy('id_modulo')
                    ->map(function ($grupoModulo) {
                        $primero = $grupoModulo->first();
                        return [
                            'modulo'      => $primero->modulo?->modulo,
                            'id_modulo'   => $primero->id_modulo,
                            'formularios' => $grupoModulo
                                ->groupBy('id_formulario')
                                ->map(function ($grupoForm) {
                                    $pf = $grupoForm->first();
                                    return [
                                        'formulario'    => $pf->formulario?->formulario,
                                        'id_formulario' => $pf->id_formulario,
                                        'acciones'      => $grupoForm->map(fn($p) => [
                                            'id_accion' => $p->id_accion,
                                            'accion'    => $p->accion?->accion,
                                        ])->values(),
                                    ];
                                })->values(),
                        ];
                    })->values()
            ),

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}