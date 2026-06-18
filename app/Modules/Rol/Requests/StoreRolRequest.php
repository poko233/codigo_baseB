<?php

namespace App\Modules\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRolRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'rol'         => ['required', 'string', 'max:40'],
            'descripcion' => ['nullable', 'string'],
            'estado'      => ['nullable', 'in:Activo,Inactivo'],
        ];
    }
}