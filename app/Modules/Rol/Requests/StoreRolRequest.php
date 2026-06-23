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
        $idEmpresa = (int) $this->header('X-Empresa-Id');

        return [
            // id_empresa NO se valida ni se acepta desde el body.
            // Siempre se toma del header X-Empresa-Id en el controller.
            'rol' => [
                'required',
                'string',
                'max:40',
                Rule::unique('rol')->where('id_empresa', $idEmpresa),
            ],
            'descripcion' => ['nullable', 'string'],
            'estado'      => ['nullable', 'in:Activo,Inactivo'],
        ];
    }

    public function messages(): array
    {
        return [
            'rol.unique' => 'Ya existe un rol con este nombre en la empresa.',
        ];
    }
}