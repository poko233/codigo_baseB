<?php

namespace App\Shared\Traits;

use App\Shared\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToEmpresa
{
    protected static function bootBelongsToEmpresa(): void
    {
        static::addGlobalScope(new EmpresaScope);
    }

    public static function sinFiltroEmpresa(): Builder
    {
        return static::withoutGlobalScope(EmpresaScope::class);
    }

    public static function deEmpresa(int $idEmpresa): Builder
    {
        return static::withoutGlobalScope(EmpresaScope::class)
                     ->where('id_empresa', $idEmpresa);
    }
}
