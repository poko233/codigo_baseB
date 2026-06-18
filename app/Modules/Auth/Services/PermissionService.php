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
            ->join('formulario_permiso', 'formulario_permiso.id_rol', '=', 'roles.id')
            ->join('modulos', 'modulos.id', '=', 'formulario_permiso.id_modulo')
            ->join('formularios', 'formularios.id', '=', 'formulario_permiso.id_formulario')
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