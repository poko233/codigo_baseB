<?php

namespace App\Modules\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSucursal
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $idSucursal = $request->header('X-Sucursal-Id');

        if (!$idSucursal) {
            abort(400, 'El header X-Sucursal-Id es obligatorio');
        }

        $perteneceASucursal = $user->sucursales()
            ->where('sucursal.id', (int) $idSucursal)
            ->exists();

        if (!$perteneceASucursal) {
            abort(403, 'No tienes acceso a esta sucursal.');
        }

        return $next($request);
    }
}