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

        if (!$this->permissions->userHasPermission($user, $modulo, $formulario, $accion)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return $next($request);
    }
}
