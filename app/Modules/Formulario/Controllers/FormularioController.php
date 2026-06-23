<?php

namespace App\Modules\Formulario\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Formulario\Models\Formulario;
use App\Modules\Formulario\Requests\FormularioRequest;
use App\Modules\Formulario\Resource\FormularioResource;
use App\Modules\Formulario\Services\FormularioService;
use Illuminate\Http\Request;

class FormularioController extends Controller
{
    public function __construct(private FormularioService $formularioService) {}

    public function index()
    {
        return FormularioResource::collection($this->formularioService->listar());
    }

    public function store(FormularioRequest $request)
    {
        $idEmpresa = (int) $request->header('X-Empresa-Id');
        $formulario = $this->formularioService->crear($request->validated(), $idEmpresa);
        return (new FormularioResource($formulario))->response()->setStatusCode(201);
    }

    public function show(Formulario $formulario)
    {
        return new FormularioResource($formulario->load('modulos'));
    }

    public function update(FormularioRequest $request, Formulario $formulario)
    {
        return new FormularioResource(
            $this->formularioService->actualizar($formulario, $request->validated())
        );
    }

    public function destroy(Formulario $formulario)
    {
        $this->formularioService->eliminar($formulario);
        return response()->json(['message' => 'Formulario eliminado correctamente.']);
    }
}
