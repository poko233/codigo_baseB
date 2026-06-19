<?php

namespace App\Shared\Traits;

use App\Shared\Scopes\SucursalScope;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToSucursal
{
    protected static function bootBelongsToSucursal(): void
    {
        static::addGlobalScope(new SucursalScope);
    }

    /**
     * Quita el filtro de sucursal para una consulta concreta.
     */
    public static function sinFiltroSucursal(): Builder
    {
        return static::withoutGlobalScope(SucursalScope::class);
    }

    /**
     * Filtra por una sucursal específica ignorando el header.
     */
    public static function deSucursal(int $idSucursal): Builder
    {
        return static::withoutGlobalScope(SucursalScope::class)
            ->where('id_sucursal', $idSucursal);
    }
}