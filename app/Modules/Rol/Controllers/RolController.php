<?php

namespace App\Modules\Rol\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rol\Models\Rol;
use App\Modules\Rol\Requests\StoreRolRequest;
use App\Modules\Rol\Requests\UpdateRolRequest;
use App\Modules\Rol\Resources\RolResource;
use App\Modules\Rol\Services\RolService;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function __construct(private RolService $service) {}

    public function index(Request $request)
    {
        $roles = $this->service->listar(
            $request->only(['estado', 'buscar', 'por_pagina'])
        );
        return RolResource::collection($roles);
    }

    public function show(Rol $rol)
    {
        return new RolResource($rol);
    }

    public function store(StoreRolRequest $request)
    {
        // id_empresa lo sacamos del header — ya fue validado en el middleware
        $idEmpresa = (int) $request->header('X-Empresa-Id');
        $rol = $this->service->crear($request->validated(), $idEmpresa);
        return (new RolResource($rol))->response()->setStatusCode(201);
    }

    public function update(UpdateRolRequest $request, Rol $rol)
    {
        $rol = $this->service->actualizar($rol, $request->validated());
        return new RolResource($rol);
    }

    public function destroy(Rol $rol)
    {
        $this->service->eliminar($rol);
        return response()->json(['message' => 'Rol eliminado correctamente.']);
    }
}