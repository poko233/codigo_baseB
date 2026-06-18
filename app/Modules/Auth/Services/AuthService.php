<?php
namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function attemptLogin(string $usuario, string $password): array
    {
        $user = User::where('usuario', $usuario)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'usuario' => ['Las credenciales no son correctas.'],
            ]);
        }

        if ($user->estado !== 'Activo') {
            throw ValidationException::withMessages([
                'usuario' => ['El usuario está inactivo.'],
            ]);
        }

        return [
            'user'  => $user->load(['empresa', 'rol']),
            'token' => $user->createToken('api-token')->plainTextToken,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}