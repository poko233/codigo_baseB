<?php

namespace App\Modules\Empresas\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpresaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                   => $this->id,
            'empresa'              => $this->empresa,
            'slogan'               => $this->slogan,
            'sigla'                => $this->sigla,
            'telefono'             => $this->telefono,
            'celular'              => $this->celular,
            'email'                => $this->email,
            'direccion'            => $this->direccion,
            'responsable'          => $this->responsable,
            'latitud'              => $this->latitud,
            'longitud'             => $this->longitud,
            'objeto'               => $this->objeto,
            'mision'               => $this->mision,
            'vision'               => $this->vision,
            'estado'               => $this->estado,
            'redes'                => [
                'facebook'  => $this->facebook,
                'instagram' => $this->instagram,
                'tiktok'    => $this->tiktok,
                'linkedin'  => $this->linkedin,
            ],
            'carrito'              => $this->carrito,
            'tipo_cambio'          => $this->tipo_cambio,
            'logos'                => [
                'cuadrado' => $this->logo_cuadrado,
                'largo'    => $this->logo_largo,
                'baner'    => $this->baner_inicio,
                'icono'    => $this->icono,
            ],
            'cierre'               => [
                'titulo'   => $this->titulo_cierre,
                'mensaje'  => $this->mensaje_cierre,
            ],
            'inicio'               => [
                'titulo'   => $this->titulo_inicio,
                'mensaje'  => $this->mensaje_inicio,
            ],
            'dominio'              => $this->dominio,
            'smtp_correo'          => $this->smtp_correo,
            'correo_institucional' => $this->correo_institucional,
            // pwd_institucional nunca sale en la respuesta
            'created_at'           => $this->created_at?->toDateTimeString(),
            'updated_at'           => $this->updated_at?->toDateTimeString(),
        ];
    }
}