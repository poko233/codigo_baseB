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

            // QR (base64)
            'codigo_qr' => $this->codigo_qr,

            // Empresas con sucursales anidadas
            'empresas' => $this->whenLoaded('empresas', function () {
                return $this->empresas->map(function ($empresa) {
                    $sucursales = $empresa->relationLoaded('sucursales')
                        ? $empresa->sucursales->map(fn($s) => [
                            'id' => $s->id,
                            'sucursal' => $s->sucursal,
                            'estado' => $s->estado,
                        ])->values()
                        : [];

                    return [
                        'id' => $empresa->id,
                        'empresa' => $empresa->empresa,
                        'sucursales' => $sucursales,
                    ];
                });
            }),

            // Roles (filtrados por empresa activa)
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(fn($rol) => [
                    'id' => $rol->id,
                    'rol' => $rol->rol,
                    'id_empresa' => $rol->id_empresa,
                    'estado' => $rol->estado,
                ]);
            }),
        ];
    }
}