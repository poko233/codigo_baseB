<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormularioSeeder extends Seeder
{
    public function run(): void
    {
        $formularios = [
            ['formulario' => 'Inicio',       'ruta' => '/dashboard'],
            ['formulario' => 'Roles',        'ruta' => '/roles'],
            ['formulario' => 'Modulos',      'ruta' => '/modulos'],
            ['formulario' => 'Formularios',  'ruta' => '/formularios'],
            ['formulario' => 'Usuarios',     'ruta' => '/usuarios'],
            ['formulario' => 'Empresas',     'ruta' => '/empresas'],
            ['formulario' => 'Sucursales',   'ruta' => '/sucursales'],
        ];

        foreach ($formularios as $f) {
            DB::table('formulario')->updateOrInsert(
                ['id_empresa' => 1, 'formulario' => $f['formulario']],
                [
                    'id_empresa'  => 1,
                    'ruta'        => $f['ruta'],
                    'estado'      => 'Activo',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
            $this->command->info("  ✅ Formulario {$f['formulario']} OK");
        }
    }
}
