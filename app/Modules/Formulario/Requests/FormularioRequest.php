<?php

namespace App\Modules\Formulario\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormularioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $idEmpresa = (int) $this->header('X-Empresa-Id');

        return [
            'formulario' => [
                'required',
                'string',
                'max:40',
                Rule::unique('formulario')
                    ->where('id_empresa', $idEmpresa)
                    ->ignore($this->route('formulario')),
            ],
            'descripcion' => 'nullable|string',
            'ruta'        => 'nullable|string|max:40',
            'estado'      => 'required|in:Activo,Inactivo',
            'modulos'     => 'nullable|array',
            'modulos.*'   => 'integer|exists:modulo,id',
        ];
    }

    public function messages(): array
    {
        return [
            'formulario.required' => 'El nombre del formulario es obligatorio.',
            'formulario.unique'   => 'Ya existe un formulario con este nombre en la empresa.',
            'estado.in'           => 'El estado debe ser Activo o Inactivo.',
        ];
    }
}
