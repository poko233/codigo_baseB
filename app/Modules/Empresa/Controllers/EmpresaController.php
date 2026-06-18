<?php

namespace App\Modules\Empresa\Controllers;

use App\Modules\Empresa\Models\Empresa;
use App\Modules\Empresa\Requests\StoreEmpresaRequest;
use App\Modules\Empresa\Requests\UpdateEmpresaRequest;
use App\Modules\Empresa\Resources\EmpresaResource;
use App\Modules\Empresa\Services\EmpresaService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function __construct(private EmpresaService $service) {}

    public function index(Request $request)
    {
        $empresas = $this->service->listar($request->only(['estado', 'buscar', 'por_pagina']));
        return EmpresaResource::collection($empresas);
    }
    public function show(Empresa $empresa)
    {
        return new EmpresaResource($empresa);
    }

    public function store(StoreEmpresaRequest $request)
    {
        $empresa = $this->service->crear($request->validated());
        return (new EmpresaResource($empresa))->response()->setStatusCode(201);
    }

    public function update(UpdateEmpresaRequest $request, Empresa $empresa)
    {
        $empresa = $this->service->actualizar($empresa, $request->validated());
        return new EmpresaResource($empresa);
    }

    public function destroy(Empresa $empresa)
    {
        $this->service->eliminar($empresa);
        return response()->json(['message' => 'Empresa eliminada correctamente.']);
    }
}