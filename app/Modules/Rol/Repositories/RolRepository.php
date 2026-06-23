<?php

namespace App\Modules\Rol\Repositories;

use App\Modules\Formulario\Models\Formulario;
use App\Modules\Modulo\Models\Modulo;
use App\Modules\Rol\Models\Rol;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RolRepository
{
    public function paginar(int $idEmpresa, array $filtros): LengthAwarePaginator
    {
        $query = Rol::query()->where('id_empresa', $idEmpresa);

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['buscar'])) {
            $query->where('rol', 'ilike', '%' . $filtros['buscar'] . '%');
        }

        $porPagina = (int) ($filtros['por_pagina'] ?? 15);

        return $query->orderBy('rol')->paginate($porPagina);
    }

    public function conPermisos(Rol $rol): Rol
    {
        return $rol->load([
            'permisos' => function ($query) {
                $query->whereHas('modulo', fn ($q) => $q->where('estado', 'Activo'))
                      ->whereHas('formulario', fn ($q) => $q->where('estado', 'Activo'));
            },
            'permisos.modulo',
            'permisos.formulario',
            'permisos.accion',
        ]);
    }

    public function crear(array $datos): Rol
    {
        return Rol::create($datos);
    }

    public function actualizar(Rol $rol, array $datos): Rol
    {
        $rol->update($datos);
        return $rol;
    }

    /**
     * Recibe el array agrupado de permisos y sincroniza.
     * Formato: [['id_modulo' => 1, 'id_formulario' => 3, 'acciones' => [1, 2]], ...]
     * Si envías [] quita todos los permisos del rol.
     * Valida que módulo y formulario pertenezcan a la empresa del rol.
     */
    public function sincronizarPermisos(Rol $rol, array $permisos): void
    {
        DB::transaction(function () use ($rol, $permisos) {
            $idEmpresa = $rol->id_empresa;

            if (!empty($permisos)) {
                $idsModulo     = array_unique(array_column($permisos, 'id_modulo'));
                $idsFormulario = array_unique(array_column($permisos, 'id_formulario'));

                // deEmpresa() bypasses the global scope y aplica un where explícito.
                $modulosValidos = Modulo::deEmpresa($idEmpresa)
                    ->whereIn('id', $idsModulo)
                    ->pluck('id')
                    ->all();

                $formulariosValidos = Formulario::deEmpresa($idEmpresa)
                    ->whereIn('id', $idsFormulario)
                    ->pluck('id')
                    ->all();

                foreach ($permisos as $permiso) {
                    if (!in_array($permiso['id_modulo'], $modulosValidos, true)) {
                        abort(422, "El módulo {$permiso['id_modulo']} no pertenece a esta empresa.");
                    }
                    if (!in_array($permiso['id_formulario'], $formulariosValidos, true)) {
                        abort(422, "El formulario {$permiso['id_formulario']} no pertenece a esta empresa.");
                    }
                }
            }

            $rol->permisos()->delete();

            if (!empty($permisos)) {
                $filas = [];
                foreach ($permisos as $permiso) {
                    foreach ($permiso['acciones'] as $idAccion) {
                        $filas[] = [
                            'id_rol'        => $rol->id,
                            'id_modulo'     => $permiso['id_modulo'],
                            'id_formulario' => $permiso['id_formulario'],
                            'id_accion'     => $idAccion,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ];
                    }
                }
                $rol->permisos()->getModel()::insert($filas);
            }
        });
    }

    public function eliminar(Rol $rol): void
    {
        $rol->delete();
    }
}
