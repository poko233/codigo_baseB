<?php

namespace App\Modules\Rol\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncModulosRolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'modulo_ids'   => ['present', 'array'],
            'modulo_ids.*' => ['integer', 'exists:modulo,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'modulo_ids.present' => 'Debes enviar el campo modulo_ids, aunque sea un array vacío.',
            'modulo_ids.*.exists' => 'Uno de los módulos enviados no existe.',
        ];
    }
}
