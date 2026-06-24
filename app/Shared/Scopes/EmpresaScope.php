<?php

namespace App\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EmpresaScope implements Scope
{
    /*
     * Filtrado automático por empresa DESACTIVADO.
     * La estructura de BD (id_empresa en todas las tablas) se preserva
     * para reactivar multi-empresa en el futuro.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // no-op: empresa filtering disabled
    }
}
