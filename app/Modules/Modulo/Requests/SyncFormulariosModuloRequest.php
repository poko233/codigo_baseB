<?php

namespace App\Modules\Modulo\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncFormulariosModuloRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'formulario_ids'   => ['present', 'array'],
            'formulario_ids.*' => ['integer', 'exists:formulario,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'formulario_ids.present' => 'Debes enviar el campo formulario_ids, aunque sea un array vacío.',
            'formulario_ids.*.exists' => 'Uno de los formularios enviados no existe.',
        ];
    }
}
