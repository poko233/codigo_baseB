<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('empresa')->updateOrInsert(
            ['id' => 1],
            [
                'empresa'    => 'MetaSoft Bolivia',
                'sigla'      => 'MSB',
                'email'      => 'contacto@metasoft.bo',
                'telefono'   => '59123456',
                'direccion'  => 'Av. Principal #100, La Paz',
                'estado'     => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $this->command->info('✅ Empresa OK');
    }
}
