<?php

namespace App\Modules\Permiso\Services;

use App\Modules\Permiso\Models\FormularioPermiso;
use App\Modules\Rol\Models\Rol;
use Illuminate\Support\Facades\DB;

class PermisoService
{
    public function getPermisosByRol(int $idRol)
    {
        return FormularioPermiso::with(['modulo', 'formulario', 'accion'])
            ->where('id_rol', $idRol)
            ->get();
    }

    public function syncPermisos(int $idRol, array $permisos)
    {
        return DB::transaction(function () use ($idRol, $permisos) {
            Rol::findOrFail($idRol);

            FormularioPermiso::where('id_rol', $idRol)->delete();

            $permisosData = [];
            foreach ($permisos as $permiso) {
                $permisosData[] = [
                    'id_rol'        => $idRol,
                    'id_modulo'     => $permiso['id_modulo'],
                    'id_formulario' => $permiso['id_formulario'],
                    'id_accion'     => $permiso['id_accion'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            if (!empty($permisosData)) {
                FormularioPermiso::insert($permisosData);
            }

            return $this->getPermisosByRol($idRol);
        });
    }

    public function addPermiso(int $idRol, array $permiso)
    {
        return DB::transaction(function () use ($idRol, $permiso) {
            Rol::findOrFail($idRol);

            $nuevoPermiso = FormularioPermiso::create([
                'id_rol'        => $idRol,
                'id_modulo'     => $permiso['id_modulo'],
                'id_formulario' => $permiso['id_formulario'],
                'id_accion'     => $permiso['id_accion'],
            ]);

            return $nuevoPermiso->load(['modulo', 'formulario', 'accion']);
        });
    }

    public function removeByParams(int $idRol, int $idFormulario, int $idAccion): int
    {
        return FormularioPermiso::where('id_rol', $idRol)
            ->where('id_formulario', $idFormulario)
            ->where('id_accion', $idAccion)
            ->delete();
    }
}
