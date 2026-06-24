<?php

namespace App\Modules\Rol\Repositories;

use App\Modules\Formulario\Models\Formulario;
use App\Modules\Modulo\Models\Modulo;
use App\Modules\Rol\Models\Rol;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RolRepository
{
    public function paginar(array $filtros): LengthAwarePaginator
    {
        $query = Rol::query();

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
        $modulosActivos     = Modulo::where('estado', 'Activo')->pluck('id');
        $formulariosActivos = Formulario::where('estado', 'Activo')->pluck('id');

        return $rol->load([
            'permisos' => fn ($q) => $q
                ->select('id', 'id_rol', 'id_modulo', 'id_formulario', 'id_accion')
                ->whereIn('id_modulo', $modulosActivos)
                ->whereIn('id_formulario', $formulariosActivos),
            'permisos.modulo:id,modulo,icono',
            'permisos.formulario:id,formulario,ruta',
            'permisos.accion:id,accion',
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

    public function sincronizarPermisos(Rol $rol, array $permisos): void
    {
        DB::transaction(function () use ($rol, $permisos) {
            $rol->permisos()->delete();

            if (!empty($permisos)) {
                $idsModulo     = array_unique(array_column($permisos, 'id_modulo'));
                $idsFormulario = array_unique(array_column($permisos, 'id_formulario'));

                $modulosValidos     = Modulo::whereIn('id', $idsModulo)->pluck('id')->all();
                $formulariosValidos = Formulario::whereIn('id', $idsFormulario)->pluck('id')->all();

                foreach ($permisos as $permiso) {
                    if (!in_array($permiso['id_modulo'], $modulosValidos, true)) {
                        abort(422, "El módulo {$permiso['id_modulo']} no existe.");
                    }
                    if (!in_array($permiso['id_formulario'], $formulariosValidos, true)) {
                        abort(422, "El formulario {$permiso['id_formulario']} no existe.");
                    }
                }

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

    public function todosConPermisos(): Collection
    {
        $modulosActivos     = Modulo::where('estado', 'Activo')->pluck('id');
        $formulariosActivos = Formulario::where('estado', 'Activo')->pluck('id');

        return Rol::with([
            'permisos' => fn ($q) => $q
                ->select('id', 'id_rol', 'id_modulo', 'id_formulario', 'id_accion')
                ->whereIn('id_modulo', $modulosActivos)
                ->whereIn('id_formulario', $formulariosActivos),
            'permisos.modulo:id,modulo,icono',
            'permisos.formulario:id,formulario,ruta',
            'permisos.accion:id,accion',
        ])
        ->orderBy('rol')
        ->get();
    }
}
