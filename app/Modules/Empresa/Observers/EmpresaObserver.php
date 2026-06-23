<?php

namespace App\Modules\Empresa\Observers;

use App\Modules\Empresa\Models\Empresa;
use App\Modules\Rol\Models\Rol;

class EmpresaObserver
{
    public function created(Empresa $empresa): void
    {
        $rolesDefault = [
            [
                'rol'         => 'administrador',
                'descripcion' => 'Acceso total al sistema.',
                'estado'      => 'Activo',
            ],
            [
                'rol'         => 'user',
                'descripcion' => 'Supervisión de operaciones y reportes.',
                'estado'      => 'Activo',
            ],
        ];

        foreach ($rolesDefault as $datos) {
            Rol::create([...$datos, 'id_empresa' => $empresa->id]);
        }
    }
}