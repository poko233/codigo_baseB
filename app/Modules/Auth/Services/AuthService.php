<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Intenta autenticar al usuario en una empresa concreta.
     *
     * @return array{token: string, empresa_id: int, empresa_nombre: string}
     */
    public function attemptLogin(string $usuario, string $password, string $nombreEmpresa): array
    {
        $user = User::where('usuario', $usuario)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'usuario' => ['Las credenciales no son correctas.'],
            ]);
        }

        if ($user->estado !== 'Activo') {
            throw ValidationException::withMessages([
                'usuario' => ['El usuario está inactivo.'],
            ]);
        }

        $empresa = $user->empresas()->where('empresa.empresa', $nombreEmpresa)->first();

        if (!$empresa) {
            throw ValidationException::withMessages([
                'empresa' => ['El usuario no pertenece a esta empresa.'],
            ]);
        }

        return [
            'token' => $user->createToken('api-token')->plainTextToken,
            'empresa_id' => $empresa->id,
            'empresa_nombre' => $empresa->empresa,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
