<?php

namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Resources\UserResource;
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
            'data' => new UserResource($result['user']),
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

        $user->load([
            'empresas',
            'roles' => function ($query) use ($empresaId) {
                if ($empresaId) {
                    $query->where('roles.id_empresa', (int) $empresaId);
                }
            },
        ]);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'Success',
        ]);
    }
}