<?php

namespace App\Modules\Rol\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRolRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id_empresa'  => ['required', 'integer', 'exists:empresa,id'],
            'rol'         => ['required', 'string', 'max:40'],
            'descripcion' => ['nullable', 'string'],
            'estado'      => ['nullable', 'in:Activo,Inactivo'],
        ];
    }
}