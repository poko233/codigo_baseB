<?php

namespace App\Modules\Empresas\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpresaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'empresa'              => ['sometimes', 'string', 'max:100'],
            'slogan'               => ['sometimes', 'nullable', 'string'],
            'sigla'                => ['sometimes', 'nullable', 'string', 'max:200'],
            'telefono'             => ['sometimes', 'nullable', 'string', 'max:11'],
            'celular'              => ['sometimes', 'nullable', 'string', 'max:11'],
            'email'                => ['sometimes', 'nullable', 'email', 'max:80'],
            'direccion'            => ['sometimes', 'nullable', 'string'],
            'responsable'          => ['sometimes', 'nullable', 'string', 'max:80'],
            'latitud'              => ['sometimes', 'nullable', 'string', 'max:80'],
            'longitud'             => ['sometimes', 'nullable', 'string', 'max:80'],
            'objeto'               => ['sometimes', 'nullable', 'string'],
            'mision'               => ['sometimes', 'nullable', 'string'],
            'vision'               => ['sometimes', 'nullable', 'string'],
            'estado'               => ['sometimes', 'in:Activo,Inactivo'],
            'facebook'             => ['sometimes', 'nullable', 'string', 'max:40'],
            'instagram'            => ['sometimes', 'nullable', 'string', 'max:40'],
            'tiktok'               => ['sometimes', 'nullable', 'string', 'max:40'],
            'linkedin'             => ['sometimes', 'nullable', 'string', 'max:40'],
            'carrito'              => ['sometimes', 'nullable', 'string', 'max:8'],
            'tipo_cambio'          => ['sometimes', 'nullable', 'numeric'],
            'titulo_cierre'        => ['sometimes', 'nullable', 'string', 'max:80'],
            'mensaje_cierre'       => ['sometimes', 'nullable', 'string'],
            'titulo_inicio'        => ['sometimes', 'nullable', 'string', 'max:80'],
            'mensaje_inicio'       => ['sometimes', 'nullable', 'string'],
            'dominio'              => ['sometimes', 'nullable', 'string', 'max:200'],
            'smtp_correo'          => ['sometimes', 'nullable', 'string', 'max:100'],
            'correo_institucional' => ['sometimes', 'nullable', 'string', 'max:80'],
            'pwd_institucional'    => ['sometimes', 'nullable', 'string', 'max:80'],
        ];
    }
}