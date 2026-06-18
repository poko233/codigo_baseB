<?php

namespace App\Modules\Roles\Services;

use App\Roles\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RolService
{
    public function listar(array $filtros = []): LengthAwarePaginator
    {
        return Rol::query()
            ->when($filtros['estado'] ?? null, fn($q, $v) => $q->where('estado', $v))
            ->when($filtros['buscar'] ?? null, fn($q, $v) =>
                $q->where('rol', 'like', "%{$v}%")
                  ->orWhere('descripcion', 'like', "%{$v}%")
            )
            ->orderBy('rol')
            ->paginate($filtros['por_pagina'] ?? 15);
    }

    public function crear(array $datos, int $idEmpresa): Rol
    {
        return Rol::create([...$datos, 'id_empresa' => $idEmpresa]);
    }

    public function actualizar(Rol $rol, array $datos): Rol
    {
        $rol->update($datos);
        return $rol->fresh();
    }

    public function eliminar(Rol $rol): void
    {
        $tieneUsuarios = $rol->users()->exists() ?? false;

        if ($tieneUsuarios) {
            abort(422, 'No se puede eliminar un rol con usuarios asignados.');
        }

        $rol->delete();
    }
}