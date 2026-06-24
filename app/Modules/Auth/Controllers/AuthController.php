<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Resources\UserProfileResource;
use App\Modules\Auth\Services\AuthService;
use App\Modules\Auth\Services\PermissionService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private PermissionService $permissionService
    ) {}

    public function login(LoginRequest $request)
    {
        $data   = $request->validated();
        $result = $this->authService->attemptLogin($data['usuario'], $data['password']);

        return response()->json([
            'token'   => $result['token'],
            'message' => 'Inicio de sesión exitoso',
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->load(['roles', 'sucursales']);

        return response()->json([
            'data'    => new UserProfileResource($user),
            'message' => 'Success',
        ]);
    }

    public function mePermisos(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => $this->permissionService->getPermisos($user),
        ]);
    }

    public function sidebar(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => $this->permissionService->getSidebar($user),
        ]);
    }
}
