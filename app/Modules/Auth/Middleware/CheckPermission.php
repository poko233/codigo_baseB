<?php

namespace App\Modules\Auth\Middleware;

use App\Modules\Auth\Services\PermissionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckPermission
{
    public function __construct(private PermissionService $permissions) {}

    public function handle(Request $request, Closure $next, string $modulo, string $formulario, string $accion)
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'No autenticado.');
        }

        // Bypass para super roles — no consulta formulario_permiso
        $superRoles = config('rbac.super_roles', []);
        if (!empty($superRoles)) {
            $isSuper = Cache::remember(
                "user_is_super:{$user->id}",
                600,
                fn () => $user->roles()->whereIn('rol', $superRoles)->exists()
            );
            if ($isSuper) {
                return $next($request);
            }
        }

        if (!$this->permissions->userHasPermission($user, $modulo, $formulario, $accion)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return $next($request);
    }
}
