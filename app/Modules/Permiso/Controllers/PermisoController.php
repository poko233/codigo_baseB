<?php

namespace App\Modules\Permiso\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\PermissionService;
use App\Modules\Permiso\Requests\PermisoRequest;
use App\Modules\Permiso\Resource\PermisoResource;
use App\Modules\Permiso\Services\PermisoService;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function __construct(
        private PermisoService $permisoService,
        private PermissionService $permissionService,
    ) {}

    public function index(Request $request, int $idRol)
    {
        $idEmpresa = (int) $request->header('X-Empresa-Id');
        $permisos  = $this->permisoService->getPermisosByRol($idRol, $idEmpresa);

        return response()->json([
            'data'    => PermisoResource::collection($permisos),
            'message' => 'Permisos obtenidos correctamente',
        ]);
    }

    public function addPermiso(Request $request, int $idRol)
    {
        $request->validate([
            'id_modulo'    => 'required|exists:modulo,id',
            'id_formulario' => 'required|exists:formulario,id',
            'id_accion'    => 'required|exists:accion,id',
        ]);

        $idEmpresa = (int) $request->header('X-Empresa-Id');
        $permiso   = $this->permisoService->addPermiso($idRol, $request->all(), $idEmpresa);

        $this->permissionService->forgetPermisosDeRol($idRol);

        return response()->json([
            'data'    => new PermisoResource($permiso),
            'message' => 'Permiso agregado correctamente',
        ], 201);
    }

    public function sync(PermisoRequest $request, int $idRol)
    {
        $idEmpresa = (int) $request->header('X-Empresa-Id');
        $permisos  = $this->permisoService->syncPermisos($idRol, $request->permisos, $idEmpresa);

        $this->permissionService->forgetPermisosDeRol($idRol);

        return response()->json([
            'data'    => PermisoResource::collection($permisos),
            'message' => 'Permisos sincronizados correctamente',
        ]);
    }

    public function destroy(Request $request, int $rolId, int $formularioId, int $accionId)
    {
        $idEmpresa = (int) $request->header('X-Empresa-Id');
        $this->permisoService->removeByParams($rolId, $formularioId, $accionId, $idEmpresa);

        $this->permissionService->forgetPermisosDeRol($rolId);

        return response()->json([
            'message' => 'Permiso eliminado correctamente',
        ]);
    }
}
