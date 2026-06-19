<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    public function userHasPermission(User $user, string $modulo, string $formulario, string $accion, int $idEmpresa): bool
    {
        return DB::table('user_rol')
            ->join('roles', 'roles.id', '=', 'user_rol.id_rol')
            ->join('modulo_rol', 'modulo_rol.id_rol', '=', 'roles.id')
            ->join('modulos', function ($join) use ($idEmpresa) {
                $join->on('modulos.id', '=', 'modulo_rol.id_modulo')
                    ->where('modulos.id_empresa', $idEmpresa);
            })
            ->join('formulario_permiso', 'formulario_permiso.id_rol', '=', 'roles.id')
            ->join('formularios', function ($join) use ($idEmpresa) {
                $join->on('formularios.id', '=', 'formulario_permiso.id_formulario')
                    ->where('formularios.id_empresa', $idEmpresa);
            })
            ->join('acciones', 'acciones.id', '=', 'formulario_permiso.id_accion')
            ->where('user_rol.id_user', $user->id)
            ->where('roles.id_empresa', $idEmpresa)
            ->where('roles.estado', 'Activo')
            ->where('modulos.modulo', $modulo)
            ->where('formularios.formulario', $formulario)
            ->where('acciones.accion', $accion)
            ->exists();
    }
}