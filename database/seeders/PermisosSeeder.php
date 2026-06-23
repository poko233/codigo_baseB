<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        $accionIds   = DB::table('accion')->pluck('id', 'accion');
        $moduloIds   = DB::table('modulo')->where('id_empresa', 1)->pluck('id', 'modulo');
        $todasRelaciones = DB::table('formulario_modulo')
            ->whereIn('id_modulo', $moduloIds->values())
            ->get();

        $idSuperadmin    = DB::table('rol')->where('id_empresa', 1)->where('rol', 'Superadmin')->value('id');
        $idAdministrador = DB::table('rol')->where('id_empresa', 1)->where('rol', 'Administrador')->value('id');
        $idUsuario       = DB::table('rol')->where('id_empresa', 1)->where('rol', 'Usuario')->value('id');

        // Superadmin: todas las acciones en todos los formularios
        if ($idSuperadmin) {
            DB::table('formulario_permiso')->where('id_rol', $idSuperadmin)->delete();
            $rows = [];
            foreach ($todasRelaciones as $fm) {
                foreach ($accionIds as $idAccion) {
                    $rows[] = [
                        'id_rol'        => $idSuperadmin,
                        'id_modulo'     => $fm->id_modulo,
                        'id_formulario' => $fm->id_formulario,
                        'id_accion'     => $idAccion,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
            }
            DB::table('formulario_permiso')->insert($rows);
            $this->command->info('✅ Superadmin: ' . count($rows) . ' permisos');
        }

        // Administrador: Configuracion con Ver/Crear/Editar (sin Eliminar)
        if ($idAdministrador) {
            DB::table('formulario_permiso')->where('id_rol', $idAdministrador)->delete();
            $idConfiguracion = $moduloIds['Configuracion'] ?? null;
            if ($idConfiguracion) {
                $fms = DB::table('formulario_modulo')->where('id_modulo', $idConfiguracion)->get();
                $rows = [];
                $accionesPermitidas = array_filter([
                    $accionIds['Ver']    ?? null,
                    $accionIds['Crear']  ?? null,
                    $accionIds['Editar'] ?? null,
                ]);
                foreach ($fms as $fm) {
                    foreach ($accionesPermitidas as $idAccion) {
                        $rows[] = [
                            'id_rol'        => $idAdministrador,
                            'id_modulo'     => $fm->id_modulo,
                            'id_formulario' => $fm->id_formulario,
                            'id_accion'     => $idAccion,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ];
                    }
                }
                DB::table('formulario_permiso')->insert($rows);
                $this->command->info('✅ Administrador: ' . count($rows) . ' permisos');
            }
        }

        // Usuario: Dashboard solo Ver
        if ($idUsuario) {
            DB::table('formulario_permiso')->where('id_rol', $idUsuario)->delete();
            $idDashboard = $moduloIds['Dashboard'] ?? null;
            $idVer       = $accionIds['Ver'] ?? null;
            if ($idDashboard && $idVer) {
                $fms = DB::table('formulario_modulo')->where('id_modulo', $idDashboard)->get();
                $rows = [];
                foreach ($fms as $fm) {
                    $rows[] = [
                        'id_rol'        => $idUsuario,
                        'id_modulo'     => $fm->id_modulo,
                        'id_formulario' => $fm->id_formulario,
                        'id_accion'     => $idVer,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
                if ($rows) {
                    DB::table('formulario_permiso')->insert($rows);
                }
                $this->command->info('✅ Usuario: ' . count($rows) . ' permisos');
            }
        }
    }
}
