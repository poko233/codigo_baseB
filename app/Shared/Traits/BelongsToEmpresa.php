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

    // Escapes de emergencia
    /**
     * Quita el filtro de empresa para UNA consulta específica.
     * Útil en seeds, comandos artisan o reportes cruzados.
     *
     * Ejemplo:  Modulo::sinFiltroEmpresa()->get();
     */
    public static function sinFiltroEmpresa(): Builder
    {
        return static::withoutGlobalScope(EmpresaScope::class);
    }

    /**
     * Filtra por una empresa distinta a la del header/usuario.
     * Útil en endpoints administrativos (superadmin).
     *
     * Ejemplo:  Modulo::deEmpresa(3)->get();
     */
    public static function deEmpresa(int $idEmpresa): Builder
    {
        return static::withoutGlobalScope(EmpresaScope::class)
                     ->where('id_empresa', $idEmpresa);
    }
}