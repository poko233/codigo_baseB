<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sucursal')->updateOrInsert(
            ['id_empresa' => 1, 'sucursal' => 'Central'],
            [
                'id_empresa'  => 1,
                'sucursal'    => 'Central',
                'responsable' => 'Administrador',
                'direccion'   => 'Av. Principal #100, La Paz',
                'ciudad'      => 'La Paz',
                'pais'        => 'Bolivia',
                'estado'      => 'Activo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );
        $this->command->info('✅ Sucursal OK');
    }
}
