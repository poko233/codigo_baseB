<?php
namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        return response()->json($this->authService->attemptLogin($data['usuario'], $data['password']));
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json(['message' => 'Sesión cerrada.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load(['empresa', 'rol']));
    }
}