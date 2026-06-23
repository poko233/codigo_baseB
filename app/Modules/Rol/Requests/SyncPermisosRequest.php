<?php

namespace App\Modules\Rol\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncPermisosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permisos'                 => ['present', 'array'],
            'permisos.*.id_modulo'     => ['required', 'integer', 'exists:modulo,id'],
            'permisos.*.id_formulario' => ['required', 'integer', 'exists:formulario,id'],
            'permisos.*.acciones'      => ['required', 'array', 'min:1'],
            'permisos.*.acciones.*'    => ['required', 'integer', 'exists:accion,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'permisos.present'                  => 'Debes enviar el campo permisos, aunque sea vacío [].',
            'permisos.*.id_modulo.required'      => 'Cada permiso requiere id_modulo.',
            'permisos.*.id_modulo.exists'        => 'Uno de los módulos enviados no existe.',
            'permisos.*.id_formulario.required'  => 'Cada permiso requiere id_formulario.',
            'permisos.*.id_formulario.exists'    => 'Uno de los formularios enviados no existe.',
            'permisos.*.acciones.required'       => 'Cada permiso requiere al menos una acción.',
            'permisos.*.acciones.min'            => 'Cada permiso requiere al menos una acción.',
            'permisos.*.acciones.*.exists'       => 'Una de las acciones enviadas no existe.',
        ];
    }
}
