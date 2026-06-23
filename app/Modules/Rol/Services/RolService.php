<?php

namespace App\Modules\Rol\Services;

use App\Modules\Auth\Services\PermissionService;
use App\Modules\Rol\Models\Rol;
use App\Modules\Rol\Repositories\RolRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class RolService
{
    public function __construct(
        private RolRepository $repo,
        private PermissionService $permissionService
    ) {}

    public function listar(int $idEmpresa, array $filtros): LengthAwarePaginator
    {
        return $this->repo->paginar($idEmpresa, $filtros);
    }

    public function detalle(Rol $rol): Rol
    {
        return $this->repo->conPermisos($rol);
    }

    public function crear(array $datos, int $idEmpresa): Rol
    {
        return $this->repo->crear([
            'id_empresa'  => $idEmpresa,
            'rol'         => $datos['rol'],
            'descripcion' => $datos['descripcion'] ?? null,
            'estado'      => $datos['estado']       ?? 'Activo',
        ]);
    }

    public function actualizar(Rol $rol, array $datos): Rol
    {
        unset($datos['id_empresa']);
        $actualizado = $this->repo->actualizar($rol, $datos);
         $this->permissionService->forgetTodosRolesPermisos($rol->id_empresa); 
        $this->permissionService->forgetPermisosDeRol($rol->id);
        return $actualizado;
    }

    public function sincronizarPermisos(Rol $rol, array $permisos): Rol
    {
        $this->repo->sincronizarPermisos($rol, $permisos);
        $this->permissionService->forgetPermisosDeRol($rol->id);
        $this->permissionService->forgetTodosRolesPermisos($rol->id_empresa);
        return $this->repo->conPermisos($rol);
    }

    public function eliminar(Rol $rol): void
    {
        if ($rol->usuarios()->exists()) {
            abort(422, 'No se puede eliminar un rol que tiene usuarios asignados.');
        }
        $this->permissionService->forgetPermisosDeRol($rol->id);
        $this->permissionService->forgetTodosRolesPermisos($rol->id_empresa); 
        $this->repo->eliminar($rol);
    }

    public function listarConPermisos(int $idEmpresa): \Illuminate\Support\Collection
    {
        // 1. Intenta caché primero
        $cached = $this->permissionService->getTodosRolesPermisos($idEmpresa);
        if ($cached !== null) {
            return $cached;   // ← 0 queries, respuesta instantánea
        }

        // 2. No hay caché → consulta BD
        $roles = $this->repo->todosConPermisos($idEmpresa);

        // 3. Guarda en caché para próximas llamadas
        $this->permissionService->setTodosRolesPermisos($idEmpresa, $roles);

        return $roles;
    }
}

