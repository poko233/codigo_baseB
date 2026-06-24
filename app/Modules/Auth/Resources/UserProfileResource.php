<?php

namespace App\Modules\Auth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'usuario'          => $this->usuario,
            'nombres'          => $this->nombres,
            'primer_apellido'  => $this->primer_apellido,
            'segundo_apellido' => $this->segundo_apellido,
            'ci'               => $this->ci,
            'expedido'         => $this->expedido,
            'email'            => $this->email,
            'telefono'         => $this->telefono,
            'celular'          => $this->celular,
            'direccion'        => $this->direccion,
            'genero'           => $this->genero,
            'fecha_nac'        => $this->fecha_nac,
            'foto'             => $this->foto,
            'codigo_qr'        => $this->codigo_qr,

            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(fn ($rol) => [
                    'id'     => $rol->id,
                    'rol'    => $rol->rol,
                    'estado' => $rol->estado,
                ]);
            }),

            'sucursales' => $this->whenLoaded('sucursales', function () {
                return $this->sucursales->map(fn ($s) => [
                    'id'       => $s->id,
                    'sucursal' => $s->sucursal,
                    'ciudad'   => $s->ciudad,
                    'estado'   => $s->estado,
                ]);
            }),
        ];
    }
}
