<?php

namespace App\Modules\Permiso\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_rol' => 'required|exists:rol,id',
            'permisos' => 'required|array',
            'permisos.*.id_modulo' => 'required|exists:modulo,id',
            'permisos.*.id_formulario' => 'required|exists:formulario,id',
            'permisos.*.id_accion' => 'required|exists:accion,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id_rol.required' => 'El rol es obligatorio',
            'id_rol.exists' => 'El rol no existe',
            'permisos.required' => 'Los permisos son obligatorios',
            'permisos.array' => 'Los permisos deben ser un arreglo',
            'permisos.*.id_modulo.exists' => 'El módulo no existe',
            'permisos.*.id_formulario.exists' => 'El formulario no existe',
            'permisos.*.id_accion.exists' => 'La acción no existe',
        ];
    }
}