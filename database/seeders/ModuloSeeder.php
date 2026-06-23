<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            ['modulo' => 'Dashboard',     'icono' => 'dashboard', 'descripcion' => 'Panel principal del sistema'],
            ['modulo' => 'Configuracion', 'icono' => 'settings',  'descripcion' => 'Panel de administración del sistema'],
        ];

        foreach ($modulos as $m) {
            DB::table('modulo')->updateOrInsert(
                ['id_empresa' => 1, 'modulo' => $m['modulo']],
                [
                    'id_empresa'  => 1,
                    'descripcion' => $m['descripcion'],
                    'icono'       => $m['icono'],
                    'estado'      => 'Activo',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
            $this->command->info("  ✅ Modulo {$m['modulo']} OK");
        }
    }
}
