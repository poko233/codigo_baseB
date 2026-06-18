<?php

namespace App\Modules\Empresa\Services;

use App\Modules\empresa\Models\Empresa;
use Illuminate\Pagination\LengthAwarePaginator;

class empresaervice
{
    public function listar(array $filtros = []): LengthAwarePaginator
    {
        return Empresa::query()
            ->when($filtros['estado'] ?? null, fn($q, $v) => $q->where('estado', $v))
            ->when($filtros['buscar'] ?? null, fn($q, $v) =>
                $q->where('empresa', 'like', "%{$v}%")
                  ->orWhere('sigla', 'like', "%{$v}%")
            )
            ->orderBy('empresa')
            ->paginate($filtros['por_pagina'] ?? 15);
    }

    public function crear(array $datos): Empresa
    {
        return Empresa::create($datos);
    }

    public function actualizar(Empresa $empresa, array $datos): Empresa
    {
        $empresa->update($datos);
        return $empresa->fresh();
    }

    public function eliminar(Empresa $empresa): void
    {
        // Soft-check: no elimina si tiene usuarios activos
        $tieneUsuarios = $empresa->user()->exists() ?? false;

        if ($tieneUsuarios) {
            abort(422, 'No se puede eliminar una empresa con usuarios asignados.');
        }

        $empresa->delete();
    }
}