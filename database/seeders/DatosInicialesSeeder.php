<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatosInicialesSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // PASO 1: Empresa
        // ============================================================
        DB::table('empresa')->insert([
            'empresa'     => 'Mi Empresa Principal',
            'sigla'       => 'MEP',
            'email'       => 'admin@miempresa.com',
            'estado'      => 'Activo',
            'tipo_cambio' => 6.96,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // ============================================================
        // PASO 2: Acciones (Ver=1, Crear=2, Editar=3, Eliminar=4)
        // ============================================================
        DB::table('accion')->insert([
            ['accion' => 'Ver',      'created_at' => now(), 'updated_at' => now()],
            ['accion' => 'Crear',    'created_at' => now(), 'updated_at' => now()],
            ['accion' => 'Editar',   'created_at' => now(), 'updated_at' => now()],
            ['accion' => 'Eliminar', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ============================================================
        // PASO 3: Roles para la empresa
        // ============================================================
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
                'descripcion' => 'Solo puede ver y editar. No puede eliminar.',
                'estado'      => 'Activo',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // ============================================================
        // PASO 4: Usuario admin
        // ============================================================
        DB::table('user')->insert([
            'usuario'          => 'admin',
            'password'         => Hash::make('admin123'),
            'ci'               => '12345678',
            'nombres'          => 'Super',
            'primer_apellido'  => 'Admin',
            'email'            => 'admin@miempresa.com',
            'estado'           => 'Activo',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // ============================================================
        // PASO 5: Vincular usuario → empresa
        // ============================================================
        DB::table('user_empresa')->insert([
            'id_user'    => 1,
            'id_empresa' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ============================================================
        // PASO 6: Vincular usuario → rol Admin
        // ============================================================
        DB::table('user_rol')->insert([
            'id_user'    => 1,
            'id_rol'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ============================================================
        // PASO 7: Módulos base
        // ============================================================
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

        // ============================================================
        // PASO 8: Formularios base
        // ============================================================
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

        // ============================================================
        // PASO 9: Vincular formularios a módulos
        // ============================================================
        DB::table('formulario_modulo')->insert([
            ['id_modulo' => 1, 'id_formulario' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_modulo' => 1, 'id_formulario' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_modulo' => 1, 'id_formulario' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_modulo' => 2, 'id_formulario' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_modulo' => 2, 'id_formulario' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ============================================================
        // PASO 10: Dar TODOS los permisos al rol Admin (id_rol=1)
        // ============================================================
        DB::table('formulario_permiso')->insert([
            // Roles - Listado: Ver, Crear, Editar, Eliminar
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 1, 'id_accion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 1, 'id_accion' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 1, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 1, 'id_accion' => 4, 'created_at' => now(), 'updated_at' => now()],
            // Roles - Detalle: Ver, Crear, Editar, Eliminar
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 2, 'id_accion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 2, 'id_accion' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 2, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 2, 'id_accion' => 4, 'created_at' => now(), 'updated_at' => now()],
            // Roles - Permisos: Editar
            ['id_rol' => 1, 'id_modulo' => 1, 'id_formulario' => 3, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            // Empresas - Listado: Ver, Crear, Editar, Eliminar
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 1, 'id_accion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 1, 'id_accion' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 1, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 1, 'id_accion' => 4, 'created_at' => now(), 'updated_at' => now()],
            // Empresas - Detalle: Ver, Editar, Eliminar
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 2, 'id_accion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 2, 'id_accion' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_rol' => 1, 'id_modulo' => 2, 'id_formulario' => 2, 'id_accion' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        echo "\n✅ DATOS INSERTADOS CORRECTAMENTE\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "👤 Usuario: admin\n";
        echo "🔑 Password: admin123\n";
        echo "🏢 Empresa: Mi Empresa Principal\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }
}
