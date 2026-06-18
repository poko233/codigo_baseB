<?php

namespace App\Modules\Rol\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'rol'         => ['sometimes', 'string', 'max:40'],
            'descripcion' => ['sometimes', 'nullable', 'string'],
            'estado'      => ['sometimes', 'in:Activo,Inactivo'],
        ];
    }
}