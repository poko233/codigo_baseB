<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Ver', 'Crear', 'Editar', 'Eliminar'] as $accion) {
            DB::table('accion')->updateOrInsert(['accion' => $accion], ['accion' => $accion]);
        }
        $this->command->info('✅ Acciones OK');
    }
}
