<?php

namespace App\Modules\Modulo\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\SidebarCacheService;
use App\Modules\Formulario\Models\Formulario;
use App\Modules\Modulo\Models\Modulo;
use App\Modules\Modulo\Requests\SyncFormulariosModuloRequest;
use Illuminate\Support\Facades\DB;

class FormularioModuloController extends Controller
{
    public function __construct(private SidebarCacheService $sidebarCache) {}

    public function show(Modulo $modulo)
    {
        $modulo->load('formularios:id,formulario,ruta,descripcion,estado');

        return response()->json([
            'data' => [
                'modulo'      => ['id' => $modulo->id, 'modulo' => $modulo->modulo],
                'formularios' => $modulo->formularios->map(fn ($f) => [
                    'id'          => $f->id,
                    'formulario'  => $f->formulario,
                    'ruta'        => $f->ruta,
                    'descripcion' => $f->descripcion,
                    'estado'      => $f->estado,
                ]),
            ],
            'message' => 'Formularios del módulo obtenidos correctamente',
        ]);
    }

    public function sync(SyncFormulariosModuloRequest $request, Modulo $modulo)
    {
        $ids = $request->validated()['formulario_ids'];

        DB::transaction(function () use ($modulo, $ids) {
            $modulo->formularios()->sync($ids);
        });

        $this->sidebarCache->forgetByModulo($modulo->id);

        $modulo->load('formularios:id,formulario,ruta,descripcion,estado');

        return response()->json([
            'data' => [
                'modulo'      => ['id' => $modulo->id, 'modulo' => $modulo->modulo],
                'formularios' => $modulo->formularios->map(fn ($f) => [
                    'id'          => $f->id,
                    'formulario'  => $f->formulario,
                    'ruta'        => $f->ruta,
                    'descripcion' => $f->descripcion,
                    'estado'      => $f->estado,
                ]),
            ],
            'message' => 'Formularios del módulo sincronizados correctamente',
        ]);
    }

    public function destroy(Modulo $modulo, Formulario $formulario)
    {
        DB::transaction(function () use ($modulo, $formulario) {
            $modulo->formularios()->detach($formulario->id);
        });

        $this->sidebarCache->forgetByModulo($modulo->id);

        return response()->json(['message' => 'Formulario desasignado del módulo correctamente.']);
    }
}
