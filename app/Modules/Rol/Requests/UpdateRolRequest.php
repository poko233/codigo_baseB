<?php

namespace App\Modules\Rol\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rol'         => ['sometimes', 'string', 'max:40', Rule::unique('rol')->ignore($this->route('rol'))],
            'descripcion' => ['sometimes', 'nullable', 'string'],
            'estado'      => ['sometimes', 'in:Activo,Inactivo'],
        ];
    }

    public function messages(): array
    {
        return [
            'rol.unique' => 'Ya existe un rol con este nombre.',
        ];
    }
}
