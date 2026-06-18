<?php

namespace App\Modules\Empresas\Observers;

use App\Modules\Empresas\Models\Empresa;
use App\Modules\Roles\Models\Rol;

class EmpresaObserver
{
    public function created(Empresa $empresa): void
    {
        $rolesDefault = [
            [
                'rol'         => 'admin',
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