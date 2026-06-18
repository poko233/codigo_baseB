<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    public function userHasPermission(User $user, string $modulo, string $formulario, string $accion, int $idEmpresa): bool
    {
        return DB::table('user_rol')
            ->join('rol', 'rol.id', '=', 'user_rol.id_rol')
            ->join('formulario_permiso', 'formulario_permiso.id_rol', '=', 'rol.id')
            ->join('modulo', 'modulo.id', '=', 'formulario_permiso.id_modulo')
            ->join('formulario', 'formulario.id', '=', 'formulario_permiso.id_formulario')
            ->join('accion', 'accion.id', '=', 'formulario_permiso.id_accion')
            ->where('user_rol.id_user', $user->id)
            ->where('rol.id_empresa', $idEmpresa)
            ->where('rol.estado', 'Activo')
            ->where('modulo.modulo', $modulo)
            ->where('formulario.formulario', $formulario)
            ->where('accion.accion', $accion)
            ->exists();
    }
}
