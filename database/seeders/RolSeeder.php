<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['rol' => 'Superadmin',    'descripcion' => 'Acceso total al sistema'],
            ['rol' => 'Administrador', 'descripcion' => 'Acceso administrativo'],
            ['rol' => 'Usuario',       'descripcion' => 'Acceso básico de consulta'],
        ];

        foreach ($roles as $rol) {
            DB::table('rol')->updateOrInsert(
                ['id_empresa' => 1, 'rol' => $rol['rol']],
                [
                    'id_empresa'  => 1,
                    'descripcion' => $rol['descripcion'],
                    'estado'      => 'Activo',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
            $this->command->info("  ✅ Rol {$rol['rol']} OK");
        }
    }
}
