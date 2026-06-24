<?php

namespace App\Modules\Formulario\Services;

use App\Modules\Auth\Services\PermissionService;
use App\Modules\Formulario\Models\Formulario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FormularioService
{
    public function __construct(private PermissionService $permissionService) {}

    public function listar(): Collection
    {
        return Formulario::with('modulos')->orderBy('formulario')->get();
    }

    public function crear(array $data, int $idEmpresa): Formulario
    {
        return DB::transaction(function () use ($data, $idEmpresa) {
            $formulario = Formulario::create([
                'id_empresa'  => $idEmpresa,
                'formulario'  => $data['formulario'],
                'descripcion' => $data['descripcion'] ?? null,
                'ruta'        => $data['ruta'] ?? null,
                'estado'      => $data['estado'],
            ]);

            if (!empty($data['modulos'])) {
                $formulario->modulos()->sync($data['modulos']);
            }

            return $formulario->load('modulos');
        });
    }

    public function actualizar(Formulario $formulario, array $data): Formulario
    {
        return DB::transaction(function () use ($formulario, $data) {
            $formulario->update([
                'formulario'  => $data['formulario'],
                'descripcion' => $data['descripcion'] ?? $formulario->descripcion,
                'ruta'        => $data['ruta'] ?? $formulario->ruta,
                'estado'      => $data['estado'],
            ]);

            if (array_key_exists('modulos', $data)) {
                $formulario->modulos()->sync($data['modulos'] ?? []);
            }

            $this->invalidarCacheDeFormulario($formulario->id);

            return $formulario->load('modulos');
        });
    }

    public function eliminar(Formulario $formulario): void
    {
        if ($formulario->permisos()->exists()) {
            abort(422, 'No se puede eliminar el formulario porque tiene permisos asignados a roles.');
        }

        $this->invalidarCacheDeFormulario($formulario->id);
        $formulario->delete();
    }

    private function invalidarCacheDeFormulario(int $idFormulario): void
    {
        $rolesAfectados = DB::table('formulario_permiso')
            ->where('id_formulario', $idFormulario)
            ->distinct()
            ->pluck('id_rol');

        foreach ($rolesAfectados as $idRol) {
            $this->permissionService->forgetPermisosDeRol($idRol);
        }
    }
}
