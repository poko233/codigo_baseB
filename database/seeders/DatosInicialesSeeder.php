<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatosInicialesSeeder extends Seeder
{
    public function run(): void
    {
        // ========== EMPRESA ==========
        DB::table('empresa')->insert([
            'empresa'     => 'Mi Empresa Principal',
            'sigla'       => 'MEP',
            'email'       => 'admin@miempresa.com',
            'estado'      => 'Activo',
            'tipo_cambio' => 6.96,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // ========== ACCIONES (sin timestamps) ==========
        DB::table('accion')->insert([
            ['accion' => 'Ver'],
            ['accion' => 'Crear'],
            ['accion' => 'Editar'],
            ['accion' => 'Eliminar'],
        ]);

        // ========== ROLES ==========
        DB::table('rol')->insert([
            [
                'id_empresa'  => 1,
                'rol'         => 'Admin',
                'descripcion' => 'Acceso total al sistema.',
                'estado'      => 'Activo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_empresa'  => 1,
                'rol'         => 'Cajero',
                'descripcion' => 'Solo puede ver y editar.',
                'estado'      => 'Activo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // ========== USUARIO ==========
        DB::table('user')->insert([
            'usuario'         => 'admin',
            'password'        => Hash::make('admin123'),
            'ci'              => '12345678',
            'nombres'         => 'Super',
            'primer_apellido' => 'Admin',
            'email'           => 'admin@miempresa.com',
            'estado'          => 'Activo',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ========== USER ↔ EMPRESA ==========
        DB::table('user_empresa')->insert([
            'id_user'    => 1,
            'id_empresa' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========== USER ↔ ROL ==========
        DB::table('user_rol')->insert([
            'id_user'    => 1,
            'id_rol'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========== MÓDULOS ==========
        DB::table('modulo')->insert([
            [
                'id_empresa'  => 1,
                'modulo'      => 'Roles',
                'descripcion' => 'Gestión de roles',
                'estado'      => 'Activo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_empresa'  => 1,
                'modulo'      => 'Empresas',
                'descripcion' => 'Gestión de empresas',
                'estado'      => 'Activo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // ========== FORMULARIOS ==========
        DB::table('formulario')->insert([
            [
                'id_empresa'  => 1,
                'formulario'  => 'Listado',
                'descripcion' => 'Lista principal',
                'estado'      => 'Activo',
                'ruta'        => '/listado',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_empresa'  => 1,
                'formulario'  => 'Detalle',
                'descripcion' => 'Vista detalle',
                'estado'      => 'Activo',
                'ruta'        => '/detalle',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_empresa'  => 1,
                'formulario'  => 'Permisos',
                'descripcion' => 'Gestión permisos',
                'estado'      => 'Activo',
                'ruta'        => '/permisos',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // ========== FORMULARIO ↔ MÓDULO ==========
        DB::table('formulario_modulo')->insert([
            ['id_modulo' => 1, 'id_formulario' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_modulo' => 1, 'id_formulario' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_modulo' => 1, 'id_formulario' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_modulo' => 2, 'id_formulario' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_modulo' => 2, 'id_formulario' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ========== PERMISOS AL ROL ADMIN ==========
        DB::table('formulario_permiso')->insert([
            // Roles - Listado
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 1, 'id_accion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 1, 'id_accion' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 1, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 1, 'id_accion' => 4, 'created_at' => now(), 'updated_at' => now()],
            // Roles - Detalle
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 2, 'id_accion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 2, 'id_accion' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 2, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 2, 'id_accion' => 4, 'created_at' => now(), 'updated_at' => now()],
            // Roles - Permisos
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 3, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            // Empresas - Listado
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 1, 'id_accion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 1, 'id_accion' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 1, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 1, 'id_accion' => 4, 'created_at' => now(), 'updated_at' => now()],
            // Empresas - Detalle
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 2, 'id_accion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 2, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 2, 'id_accion' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        echo "\n✅ DATOS INSERTADOS\n";
        echo "👤 admin / admin123\n\n";
    }
}
