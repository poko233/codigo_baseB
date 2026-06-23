<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeguridadSeeder extends Seeder
{
    public function run(): void
    {
        $idEmpresa     = 1;
        $usuarioAdmin  = 'admin';
        $passwordAdmin = 'admin123';
        $ciAdmin       = '00000001';

        DB::transaction(function () use ($idEmpresa, $usuarioAdmin, $passwordAdmin, $ciAdmin) {

            foreach (['Ver','Crear','Editar','Eliminar'] as $a) {
                DB::table('accion')->updateOrInsert(['accion' => $a], ['accion' => $a]);
            }
            $this->command->info('✅ Acciones OK');

            $idMod = DB::table('modulo')->where('id_empresa',$idEmpresa)->where('modulo','Configuracion')->value('id')
                ?? DB::table('modulo')->insertGetId(['id_empresa'=>$idEmpresa,'modulo'=>'Configuracion','descripcion'=>'Panel de administración','icono'=>'settings','estado'=>'Activo','created_at'=>now(),'updated_at'=>now()]);
            $this->command->info("✅ Modulo Configuracion id={$idMod}");

            $forms = [
                ['nombre'=>'Roles',       'ruta'=>'/roles'],
                ['nombre'=>'Modulos',     'ruta'=>'/modulos'],
                ['nombre'=>'Formularios', 'ruta'=>'/formularios'],
                ['nombre'=>'Usuarios',    'ruta'=>'/users'],
                ['nombre'=>'Empresas',    'ruta'=>'/empresas'],
            ];

            $formIds = [];
            foreach ($forms as $f) {
                $id = DB::table('formulario')->where('id_empresa',$idEmpresa)->where('formulario',$f['nombre'])->value('id')
                    ?? DB::table('formulario')->insertGetId(['id_empresa'=>$idEmpresa,'formulario'=>$f['nombre'],'ruta'=>$f['ruta'],'estado'=>'Activo','created_at'=>now(),'updated_at'=>now()]);
                DB::table('formulario_modulo')->updateOrInsert(['id_modulo'=>$idMod,'id_formulario'=>$id],['created_at'=>now(),'updated_at'=>now()]);
                $formIds[] = $id;
                $this->command->info("  ✅ Formulario {$f['nombre']} id={$id}");
            }

            $idRol = DB::table('rol')->where('id_empresa',$idEmpresa)->where('rol','Administrador')->value('id')
                ?? DB::table('rol')->insertGetId(['id_empresa'=>$idEmpresa,'rol'=>'Administrador','descripcion'=>'Acceso total','estado'=>'Activo','created_at'=>now(),'updated_at'=>now()]);
            $this->command->info("✅ Rol Administrador id={$idRol}");

            DB::table('formulario_permiso')->where('id_rol',$idRol)->where('id_modulo',$idMod)->delete();
            $accionIds = DB::table('accion')->pluck('id');
            $filas = [];
            foreach ($formIds as $fid) {
                foreach ($accionIds as $aid) {
                    $filas[] = ['id_rol'=>$idRol,'id_modulo'=>$idMod,'id_formulario'=>$fid,'id_accion'=>$aid,'created_at'=>now(),'updated_at'=>now()];
                }
            }
            DB::table('formulario_permiso')->insert($filas);
            $this->command->info('✅ '.count($filas).' permisos insertados');

            $idUser = DB::table('user')->where('usuario',$usuarioAdmin)->value('id');
            if (!$idUser) {
                $idUser = DB::table('user')->insertGetId(['usuario'=>$usuarioAdmin,'password'=>Hash::make($passwordAdmin),'ci'=>$ciAdmin,'nombres'=>'Administrador','primer_apellido'=>'Sistema','estado'=>'Activo','created_at'=>now(),'updated_at'=>now()]);
                $this->command->info("✅ Usuario '{$usuarioAdmin}' creado");
            } else {
                $this->command->info("ℹ️  Usuario '{$usuarioAdmin}' ya existe");
            }
            DB::table('user_empresa')->updateOrInsert(['id_user'=>$idUser,'id_empresa'=>$idEmpresa],['created_at'=>now(),'updated_at'=>now()]);
            DB::table('user_rol')->updateOrInsert(['id_user'=>$idUser,'id_rol'=>$idRol],['created_at'=>now(),'updated_at'=>now()]);
            $this->command->info("✅ Usuario '{$usuarioAdmin}' → Administrador");

            $this->command->newLine();
            $this->command->info('══════════════════════════════');
            $this->command->info("  usuario:  {$usuarioAdmin}");
            $this->command->info("  password: {$passwordAdmin}");
            $this->command->info('══════════════════════════════');
        });
    }
}
