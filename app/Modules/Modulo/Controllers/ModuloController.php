<?php

namespace App\Modules\Modulo\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Modulo\Models\Modulo;
use App\Modules\Modulo\Requests\ModuloRequest;
use App\Modules\Modulo\Resource\ModuloResource;
use App\Modules\Modulo\Services\ModuloService;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    public function __construct(private ModuloService $moduloService) {}

    public function index()
    {
        return ModuloResource::collection($this->moduloService->listar());
    }

    public function store(ModuloRequest $request)
    {
        $idEmpresa = (int) $request->header('X-Empresa-Id');
        $modulo = $this->moduloService->crear($request->validated(), $idEmpresa);
        return (new ModuloResource($modulo))->response()->setStatusCode(201);
    }

    public function show(Modulo $modulo)
    {
        return new ModuloResource($modulo->load('formularios'));
    }

    public function update(ModuloRequest $request, Modulo $modulo)
    {
        return new ModuloResource($this->moduloService->actualizar($modulo, $request->validated()));
    }

    public function destroy(Modulo $modulo)
    {
        $this->moduloService->eliminar($modulo);
        return response()->json(['message' => 'Módulo eliminado correctamente.']);
    }
}
