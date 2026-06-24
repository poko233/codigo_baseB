<?php

namespace App\Modules\Rol\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\SidebarCacheService;
use App\Modules\Modulo\Models\Modulo;
use App\Modules\Rol\Models\Rol;
use App\Modules\Rol\Requests\SyncModulosRolRequest;
use Illuminate\Support\Facades\DB;

class ModuloRolController extends Controller
{
    public function __construct(private SidebarCacheService $sidebarCache) {}

    public function show(Rol $rol)
    {
        $rol->load('modulos:id,modulo,icono,descripcion');

        return response()->json([
            'data' => [
                'rol'     => ['id' => $rol->id, 'rol' => $rol->rol],
                'modulos' => $rol->modulos->map(fn ($m) => [
                    'id'          => $m->id,
                    'modulo'      => $m->modulo,
                    'icono'       => $m->icono,
                    'descripcion' => $m->descripcion,
                ]),
            ],
            'message' => 'Módulos del rol obtenidos correctamente',
        ]);
    }

    public function sync(SyncModulosRolRequest $request, Rol $rol)
    {
        $ids = $request->validated()['modulo_ids'];

        DB::transaction(function () use ($rol, $ids) {
            $rol->modulos()->sync($ids);
        });

        $this->sidebarCache->forgetRol($rol->id);

        $rol->load('modulos:id,modulo,icono,descripcion');

        return response()->json([
            'data' => [
                'rol'     => ['id' => $rol->id, 'rol' => $rol->rol],
                'modulos' => $rol->modulos->map(fn ($m) => [
                    'id'          => $m->id,
                    'modulo'      => $m->modulo,
                    'icono'       => $m->icono,
                    'descripcion' => $m->descripcion,
                ]),
            ],
            'message' => 'Módulos del rol sincronizados correctamente',
        ]);
    }

    public function destroy(Rol $rol, Modulo $modulo)
    {
        DB::transaction(function () use ($rol, $modulo) {
            $rol->modulos()->detach($modulo->id);
        });

        $this->sidebarCache->forgetRol($rol->id);

        return response()->json(['message' => 'Módulo desasignado del rol correctamente.']);
    }
}
