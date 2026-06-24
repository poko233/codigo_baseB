<?php

namespace App\Modules\Rol\Services;

use App\Modules\Auth\Services\PermissionService;
use App\Modules\Rol\Models\Rol;
use App\Modules\Rol\Repositories\RolRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RolService
{
    public function __construct(
        private RolRepository $repo,
        private PermissionService $permissionService
    ) {}

    public function listar(array $filtros): LengthAwarePaginator
    {
        return $this->repo->paginar($filtros);
    }

    public function detalle(Rol $rol): Rol
    {
        return $this->repo->conPermisos($rol);
    }

    public function crear(array $datos): Rol
    {
        return $this->repo->crear([
            'id_empresa'  => 1,
            'rol'         => $datos['rol'],
            'descripcion' => $datos['descripcion'] ?? null,
            'estado'      => $datos['estado']       ?? 'Activo',
        ]);
    }

    public function actualizar(Rol $rol, array $datos): Rol
    {
        unset($datos['id_empresa']);
        $actualizado = $this->repo->actualizar($rol, $datos);
        $this->permissionService->forgetPermisosDeRol($rol->id);
        return $actualizado;
    }

    public function sincronizarPermisos(Rol $rol, array $permisos): Rol
    {
        $this->repo->sincronizarPermisos($rol, $permisos);
        $this->permissionService->forgetPermisosDeRol($rol->id);
        return $this->repo->conPermisos($rol);
    }

    public function eliminar(Rol $rol): void
    {
        if ($rol->usuarios()->exists()) {
            abort(422, 'No se puede eliminar un rol que tiene usuarios asignados.');
        }
        $this->permissionService->forgetPermisosDeRol($rol->id);
        $this->repo->eliminar($rol);
    }

    public function listarConPermisos(): Collection
    {
        return $this->repo->todosConPermisos();
    }
}
