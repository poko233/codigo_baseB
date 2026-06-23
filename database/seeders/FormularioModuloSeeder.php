<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormularioModuloSeeder extends Seeder
{
    public function run(): void
    {
        $idDashboard     = DB::table('modulo')->where('id_empresa', 1)->where('modulo', 'Dashboard')->value('id');
        $idConfiguracion = DB::table('modulo')->where('id_empresa', 1)->where('modulo', 'Configuracion')->value('id');

        $mapa = [
            $idDashboard     => ['Inicio'],
            $idConfiguracion => ['Roles', 'Modulos', 'Formularios', 'Usuarios', 'Empresas', 'Sucursales'],
        ];

        foreach ($mapa as $idModulo => $formularios) {
            if (!$idModulo) {
                continue;
            }
            foreach ($formularios as $nombre) {
                $idFormulario = DB::table('formulario')
                    ->where('id_empresa', 1)
                    ->where('formulario', $nombre)
                    ->value('id');

                if (!$idFormulario) {
                    continue;
                }

                DB::table('formulario_modulo')->updateOrInsert(
                    ['id_modulo' => $idModulo, 'id_formulario' => $idFormulario],
                    ['created_at' => now(), 'updated_at' => now()]
                );
                $this->command->info("  ✅ {$nombre} → modulo #{$idModulo}");
            }
        }
    }
}
