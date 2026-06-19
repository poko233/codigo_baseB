<?php

namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Resources\UserProfileResource;
use App\Modules\Auth\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $result = $this->authService->attemptLogin(
            $data['usuario'],
            $data['password'],
            $data['empresa']
        );

        return response()->json([
            'token' => $result['token'],
            'empresa' => [
                'id' => $result['empresa_id'],
                'nombre' => $result['empresa_nombre'],
            ],
            'message' => 'Inicio de sesión exitoso',
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $empresaId = $request->header('X-Empresa-Id');

        // Cargar empresas (solo id y nombre)
        $user->load([
            'empresas' => function ($query) {
                $query->select('empresa.id', 'empresa.empresa');
            },
            'roles' => function ($query) use ($empresaId) {
                if ($empresaId) {
                    $query->where('rol.id_empresa', (int) $empresaId);
                }
                $query->select('rol.id', 'rol.rol', 'rol.id_empresa', 'rol.estado');
            },
        ]);

        // Obtener todas las sucursales del usuario (sin filtrar por empresa activa)
        $sucursales = \App\Modules\Sucursal\Models\Sucursal::select(
            'sucursal.id',
            'sucursal.sucursal',
            'sucursal.id_empresa',
            'sucursal.estado'
        )
            ->join('user_sucursal', 'user_sucursal.id_sucursal', '=', 'sucursal.id')
            ->where('user_sucursal.id_user', $user->id)
            ->get()
            ->groupBy('id_empresa');

        // Anidar sucursales dentro de cada empresa
        foreach ($user->empresas as $empresa) {
            $empresaSucursales = $sucursales->get($empresa->id, collect([]));
            $empresa->setRelation('sucursales', $empresaSucursales);
        }

        return response()->json([
            'data' => new UserProfileResource($user),
            'message' => 'Success',
        ]);
    }
}
