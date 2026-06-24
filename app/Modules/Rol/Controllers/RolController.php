<?php

namespace App\Modules\Rol\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rol\Models\Rol;
use App\Modules\Rol\Requests\StoreRolRequest;
use App\Modules\Rol\Requests\SyncPermisosRequest;
use App\Modules\Rol\Requests\UpdateRolRequest;
use App\Modules\Rol\Resource\RolResource;
use App\Modules\Rol\Services\RolService;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function __construct(private RolService $service) {}

    public function index(Request $request)
    {
        $roles = $this->service->listar($request->only(['estado', 'buscar', 'por_pagina']));
        return RolResource::collection($roles);
    }

    public function show(Rol $rol)
    {
        return new RolResource($this->service->detalle($rol));
    }

    public function store(StoreRolRequest $request)
    {
        $rol = $this->service->crear($request->validated());
        return (new RolResource($rol))->response()->setStatusCode(201);
    }

    public function update(UpdateRolRequest $request, Rol $rol)
    {
        return new RolResource($this->service->actualizar($rol, $request->validated()));
    }

    public function getPermisos(Rol $rol)
    {
        return new RolResource($this->service->detalle($rol));
    }

    public function syncPermisos(SyncPermisosRequest $request, Rol $rol)
    {
        $rol = $this->service->sincronizarPermisos($rol, $request->validated()['permisos']);
        return new RolResource($rol);
    }

    public function destroy(Rol $rol)
    {
        $this->service->eliminar($rol);
        return response()->json(['message' => 'Rol eliminado correctamente.']);
    }

    public function todosConPermisos(Request $request)
    {
        $roles = $this->service->listarConPermisos();
        return RolResource::collection($roles);
    }
}
