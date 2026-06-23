<?php

namespace App\Modules\Permiso\Services;

use App\Modules\Permiso\Models\FormularioPermiso;
use App\Modules\Rol\Models\Rol;
use Illuminate\Support\Facades\DB;

class PermisoService
{
    public function getPermisosByRol(int $idRol, int $idEmpresa)
    {
        return FormularioPermiso::with(['modulo', 'formulario', 'accion'])
            ->where('id_rol', $idRol)
            ->byEmpresa($idEmpresa)
            ->get();
    }

    public function syncPermisos(int $idRol, array $permisos, int $idEmpresa)
    {
        return DB::transaction(function () use ($idRol, $permisos, $idEmpresa) {
            // Verificar que el rol pertenece a la empresa
            $rol = Rol::where('id_empresa', $idEmpresa)->findOrFail($idRol);

            // Eliminar permisos existentes del rol
            FormularioPermiso::where('id_rol', $idRol)->delete();

            // Crear nuevos permisos
            $permisosData = [];
            foreach ($permisos as $permiso) {
                $permisosData[] = [
                    'id_rol' => $idRol,
                    'id_modulo' => $permiso['id_modulo'],
                    'id_formulario' => $permiso['id_formulario'],
                    'id_accion' => $permiso['id_accion'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            FormularioPermiso::insert($permisosData);

            return $this->getPermisosByRol($idRol, $idEmpresa);
        });
    }

    public function addPermiso(int $idRol, array $permiso, int $idEmpresa)
    {
        return DB::transaction(function () use ($idRol, $permiso, $idEmpresa) {
            $rol = Rol::where('id_empresa', $idEmpresa)->findOrFail($idRol);

            $nuevoPermiso = FormularioPermiso::create([
                'id_rol' => $idRol,
                'id_modulo' => $permiso['id_modulo'],
                'id_formulario' => $permiso['id_formulario'],
                'id_accion' => $permiso['id_accion'],
            ]);

            return $nuevoPermiso->load(['modulo', 'formulario', 'accion']);
        });
    }

    public function removePermiso(int $idPermiso, int $idEmpresa)
    {
        return FormularioPermiso::byEmpresa($idEmpresa)
            ->where('id', $idPermiso)
            ->delete();
    }
}