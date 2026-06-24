<?php

namespace App\Modules\Modulo\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModuloRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'modulo' => [
                'required',
                'string',
                'max:40',
                Rule::unique('modulo')->ignore($this->route('modulo')),
            ],
            'descripcion'   => 'nullable|string',
            'icono'         => 'nullable|string',
            'estado'        => 'required|in:Activo,Inactivo',
            'formularios'   => 'nullable|array',
            'formularios.*' => 'integer|exists:formulario,id',
        ];
    }

    public function messages(): array
    {
        return [
            'modulo.required' => 'El nombre del módulo es obligatorio.',
            'modulo.unique'   => 'Ya existe un módulo con este nombre.',
            'estado.in'       => 'El estado debe ser Activo o Inactivo.',
        ];
    }
}
