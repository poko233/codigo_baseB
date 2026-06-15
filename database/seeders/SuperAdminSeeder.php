<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Único rol de la plantilla base
        DB::table('Rol')->updateOrInsert(
            ['id' => 1],
            [
                'rol'         => 'Administrador',
                'descripcion' => 'Acceso total al sistema. Gestiona usuarios, roles, módulos, formularios y configuraciones generales.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );

        // Super Admin en tabla User
        $userId = DB::table('User')->insertGetId([
            'usuario'          => 'admin',
            'password'         => Hash::make('admin123'),
            'ci'               => '00000000',
            'nombres'          => 'Super',
            'apellido_paterno' => 'Admin',
            'apellido_materno' => 'Fundador',
            'genero'           => 'masculino',
            'fecha_nac'        => '1990-01-01',
            'estado'           => 'activo',
            'verificacion'     => '1',
            'expedido'         => 'cbba',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // Asignar rol Administrador al super admin
        DB::table('UserRol')->insert([
            'id_user'    => $userId,
            'id_rol'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}