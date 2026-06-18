<?php

namespace App\Modules\Auth\Middleware;

use App\Modules\Auth\Services\PermissionService;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function __construct(private PermissionService $permissions) {}

    public function handle(Request $request, Closure $next, string $modulo, string $formulario, string $accion)
    {
        $user = $request->user();

        $idEmpresa = $request->header('X-Empresa-Id');

        // 2. VALIDAR que el usuario realmente pertenece a esa empresa
        //    Si manda un id inventado → 403 inmediato
        $perteneceAEmpresa = $user->empresas()
            ->where('empresas.id', (int) $idEmpresa)
            ->exists();

        if (! $idEmpresa || ! $perteneceAEmpresa) {
            abort(403, 'No tienes acceso a esta empresa.');
        }

        // 3. Recién ahora verifica el permiso
        if (! $this->permissions->userHasPermission($user, $modulo, $formulario, $accion, (int) $idEmpresa)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return $next($request);
    }
}