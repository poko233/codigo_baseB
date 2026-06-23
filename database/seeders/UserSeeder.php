<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $idEmpresa  = 1;
        $idSucursal = DB::table('sucursal')->where('id_empresa', $idEmpresa)->value('id');
        $idRol      = DB::table('rol')->where('id_empresa', $idEmpresa)->where('rol', 'Superadmin')->value('id');

        $idUser = DB::table('user')->where('usuario', 'admin')->value('id');
        if (!$idUser) {
            $idUser = DB::table('user')->insertGetId([
                'usuario'         => 'admin',
                'password'        => Hash::make('admin123'),
                'ci'              => '00000001',
                'nombres'         => 'Administrador',
                'primer_apellido' => 'Sistema',
                'estado'          => 'Activo',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
            $this->command->info("✅ Usuario 'admin' creado (id={$idUser})");
        } else {
            $this->command->info("ℹ️  Usuario 'admin' ya existe (id={$idUser})");
        }

        DB::table('user_empresa')->updateOrInsert(
            ['id_user' => $idUser, 'id_empresa' => $idEmpresa],
            ['created_at' => now(), 'updated_at' => now()]
        );

        if ($idSucursal) {
            DB::table('user_sucursal')->updateOrInsert(
                ['id_user' => $idUser, 'id_sucursal' => $idSucursal],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        if ($idRol) {
            DB::table('user_rol')->updateOrInsert(
                ['id_user' => $idUser, 'id_rol' => $idRol],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $this->command->newLine();
        $this->command->info('══════════════════════════════');
        $this->command->info('  usuario:  admin');
        $this->command->info('  password: admin123');
        $this->command->info('  empresa:  MetaSoft Bolivia');
        $this->command->info('  rol:      Superadmin');
        $this->command->info('══════════════════════════════');
    }
}
