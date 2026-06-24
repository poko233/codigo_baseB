<?php

namespace App\Console\Commands;

use App\Modules\Auth\Models\User;
use App\Modules\Auth\Services\PermissionService;
use App\Modules\Rol\Models\Rol;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class AsignarRolAdmin extends Command
{
    protected $signature   = 'rbac:admin {user_id : ID del usuario al que se asignará el primer super_rol de config/rbac.php}';
    protected $description = 'Asigna el rol Administrador a un usuario (puerta de rescate para acceso perdido)';

    public function __construct(private PermissionService $permissionService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $userId = (int) $this->argument('user_id');

        $user = User::find($userId);
        if (!$user) {
            $this->error("No se encontró un usuario con id={$userId}.");
            return self::FAILURE;
        }

        $nombreRol = config('rbac.super_roles')[0] ?? 'Administrador';

        $rol = Rol::firstOrCreate(
            ['rol' => $nombreRol],
            [
                'id_empresa'  => 1,
                'descripcion' => 'Rol con acceso total al sistema.',
                'estado'      => 'Activo',
            ]
        );

        $yaAsignado = $user->roles()->where('rol.id', $rol->id)->exists();

        if ($yaAsignado) {
            $this->info("El usuario '{$user->usuario}' ya tiene el rol '{$rol->rol}'.");
        } else {
            $user->roles()->attach($rol->id);
            $this->info("Rol '{$rol->rol}' asignado a '{$user->usuario}' (id={$user->id}).");
        }

        // Invalidar toda la caché de permisos y super-status del usuario
        $this->permissionService->forgetPermisos($user->id);

        $this->info("Caché invalidada. Usuario '{$user->usuario}' ahora es {$rol->rol}.");

        return self::SUCCESS;
    }
}
