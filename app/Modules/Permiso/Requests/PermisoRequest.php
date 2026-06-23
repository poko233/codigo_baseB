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
            'permisos'                     => 'required|array',
            'permisos.*.id_modulo'         => 'required|exists:modulo,id',
            'permisos.*.id_formulario'     => 'required|exists:formulario,id',
            'permisos.*.id_accion'         => 'required|exists:accion,id',
        ];
    }

    public function messages(): array
    {
        return [
            'permisos.required'                 => 'Los permisos son obligatorios',
            'permisos.array'                    => 'Los permisos deben ser un arreglo',
            'permisos.*.id_modulo.required'     => 'El módulo es obligatorio',
            'permisos.*.id_modulo.exists'       => 'El módulo no existe',
            'permisos.*.id_formulario.required' => 'El formulario es obligatorio',
            'permisos.*.id_formulario.exists'   => 'El formulario no existe',
            'permisos.*.id_accion.required'     => 'La acción es obligatoria',
            'permisos.*.id_accion.exists'       => 'La acción no existe',
        ];
    }
}
