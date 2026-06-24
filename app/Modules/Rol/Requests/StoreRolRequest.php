<?php

namespace App\Modules\Rol\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rol'         => ['required', 'string', 'max:40', Rule::unique('rol')],
            'descripcion' => ['nullable', 'string'],
            'estado'      => ['nullable', 'in:Activo,Inactivo'],
        ];
    }

    public function messages(): array
    {
        return [
            'rol.unique' => 'Ya existe un rol con este nombre.',
        ];
    }
}
