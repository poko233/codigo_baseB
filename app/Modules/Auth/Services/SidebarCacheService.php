<?php

namespace App\Modules\Auth\Services;

use Illuminate\Support\Facades\DB;

class SidebarCacheService
{
    public function __construct(private PermissionService $permissionService) {}

    public function forgetRol(int $idRol): void
    {
        $this->permissionService->forgetPermisosDeRol($idRol);
    }

    public function forgetByModulo(int $idModulo): void
    {
        $roles = DB::table('modulo_rol')
            ->where('id_modulo', $idModulo)
            ->pluck('id_rol');

        foreach ($roles as $idRol) {
            $this->permissionService->forgetPermisosDeRol($idRol);
        }
    }
}
