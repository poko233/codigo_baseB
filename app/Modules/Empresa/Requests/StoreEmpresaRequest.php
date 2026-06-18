<?php

namespace App\Modules\Empresa\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'empresa'               => ['required', 'string', 'max:100'],
            'slogan'                => ['nullable', 'string'],
            'sigla'                 => ['nullable', 'string', 'max:200'],
            'telefono'              => ['nullable', 'string', 'max:11'],
            'celular'               => ['nullable', 'string', 'max:11'],
            'email'                 => ['nullable', 'email', 'max:80'],
            'direccion'             => ['nullable', 'string'],
            'responsable'           => ['nullable', 'string', 'max:80'],
            'latitud'               => ['nullable', 'string', 'max:80'],
            'longitud'              => ['nullable', 'string', 'max:80'],
            'objeto'                => ['nullable', 'string'],
            'mision'                => ['nullable', 'string'],
            'vision'                => ['nullable', 'string'],
            'estado'                => ['nullable', 'in:Activo,Inactivo'],
            'facebook'              => ['nullable', 'string', 'max:40'],
            'instagram'             => ['nullable', 'string', 'max:40'],
            'tiktok'                => ['nullable', 'string', 'max:40'],
            'linkedin'              => ['nullable', 'string', 'max:40'],
            'carrito'               => ['nullable', 'string', 'max:8'],
            'tipo_cambio'           => ['nullable', 'numeric'],
            'titulo_cierre'         => ['nullable', 'string', 'max:80'],
            'mensaje_cierre'        => ['nullable', 'string'],
            'titulo_inicio'         => ['nullable', 'string', 'max:80'],
            'mensaje_inicio'        => ['nullable', 'string'],
            'dominio'               => ['nullable', 'string', 'max:200'],
            'smtp_correo'           => ['nullable', 'string', 'max:100'],
            'correo_institucional'  => ['nullable', 'string', 'max:80'],
            'pwd_institucional'     => ['nullable', 'string', 'max:80'],
        ];
    }
}