<?php

namespace App\Modules\Modulo\Services;

use App\Modules\Auth\Services\PermissionService;
use App\Modules\Modulo\Models\Modulo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ModuloService
{
    public function __construct(private PermissionService $permissionService) {}

    public function listar(): Collection
    {
        return Modulo::with('formularios')->orderBy('modulo')->get();
    }

    public function crear(array $data, int $idEmpresa): Modulo
    {
        return DB::transaction(function () use ($data, $idEmpresa) {
            $modulo = Modulo::create([
                'id_empresa'  => $idEmpresa,
                'modulo'      => $data['modulo'],
                'descripcion' => $data['descripcion'] ?? null,
                'icono'       => $data['icono'] ?? null,
                'estado'      => $data['estado'],
            ]);

            if (!empty($data['formularios'])) {
                $modulo->formularios()->sync($data['formularios']);
            }

            return $modulo->load('formularios');
        });
    }

    public function actualizar(Modulo $modulo, array $data): Modulo
    {
        return DB::transaction(function () use ($modulo, $data) {
            $modulo->update([
                'modulo'      => $data['modulo'],
                'descripcion' => $data['descripcion'] ?? $modulo->descripcion,
                'icono'       => $data['icono'] ?? $modulo->icono,
                'estado'      => $data['estado'],
            ]);

            if (array_key_exists('formularios', $data)) {
                $modulo->formularios()->sync($data['formularios'] ?? []);
            }

            $this->invalidarCacheDeModulo($modulo->id);

            return $modulo->load('formularios');
        });
    }

    public function eliminar(Modulo $modulo): void
    {
        if ($modulo->formularios()->exists()) {
            abort(422, 'No se puede eliminar el módulo porque tiene formularios asignados.');
        }

        $this->invalidarCacheDeModulo($modulo->id);
        $modulo->delete();
    }

    private function invalidarCacheDeModulo(int $idModulo): void
    {
        $rolesAfectados = DB::table('formulario_permiso')
            ->where('id_modulo', $idModulo)
            ->distinct()
            ->pluck('id_rol');

        foreach ($rolesAfectados as $idRol) {
            $this->permissionService->forgetPermisosDeRol($idRol);
        }
    }
}
