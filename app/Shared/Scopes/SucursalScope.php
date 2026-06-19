<?php

namespace App\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SucursalScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $idSucursal = $this->resolveSucursalId();

        if ($idSucursal) {
            $builder->where($model->getTable() . '.id_sucursal', (int) $idSucursal);
        }
    }

    private function resolveSucursalId(): int|null
    {
        // 1. Prioridad al header
        $fromHeader = request()->header('X-Sucursal-Id');
        if ($fromHeader && is_numeric($fromHeader)) {
            return (int) $fromHeader;
        }

        // 2. Si no hay header (consola, seeders), tomar la primera sucursal del usuario autenticado
        $user = Auth::user();
        if ($user && method_exists($user, 'sucursales')) {
            $sucursal = $user->relationLoaded('sucursales')
                ? $user->sucursales->first()
                : $user->sucursales()->first();

            return $sucursal?->id;
        }

        // 3. Sin usuario autenticado no filtramos
        return null;
    }
}