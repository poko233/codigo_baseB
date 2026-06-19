<?php

namespace App\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class EmpresaScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $idEmpresa = $this->resolveEmpresaId();

        if ($idEmpresa) {
            $builder->where($model->getTable() . '.id_empresa', (int) $idEmpresa);
        }
    }

    private function resolveEmpresaId(): int|null
    {
        $fromHeader = request()->header('X-Empresa-Id');
        if ($fromHeader && is_numeric($fromHeader)) {
            return (int) $fromHeader;
        }

        $user = Auth::user();
        if ($user && method_exists($user, 'empresa')) {
            $empresa = $user->relationLoaded('empresa')
                ? $user->empresa->first()
                : $user->empresa()->first();

            return $empresa?->id;
        }

        return null;
    }
}
