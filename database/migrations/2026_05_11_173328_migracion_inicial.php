<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     * El orden es crítico: primero entidades fuertes, luego tablas pivote, luego datos iniciales.
     */
    public function up(): void
    {
        // --- ENTIDADES FUERTES (INDEPENDIENTES) ---

        Schema::create('Empresa', function (Blueprint $table) {
            $table->id();
            $table->string('empresa', 80);
            $table->text('slogan');
            $table->string('sigla', 200);
            $table->string('telefono', 11);
            $table->string('celular', 11);
            $table->string('email', 80);
            $table->text('direccion');
            $table->string('responsable', 80);
            $table->string('latitud', 80);
            $table->string('longitud', 80);
            $table->text('objeto');
            $table->text('mision');
            $table->text('vision');
            $table->string('facebook', 40);
            $table->string('instagram', 40);
            $table->string('tiktok', 40);
            $table->string('linkedin', 40);
            $table->enum('carrito', ['activo', 'inactivo'])->default('activo');
            $table->decimal('tipo_cambio', 10, 2);
            $table->string('logo_cuadrado', 80);
            $table->string('logo_largo', 80);
            $table->string('baner_inicio', 80);
            $table->string('icono', 40);
            $table->string('titulo_cierre', 80);
            $table->text('mensaje_cierre');
            $table->string('titulo_inicio', 80);
            $table->text('mensaje_inicio');
            $table->string('dominio', 200);
            $table->string('smtp_correo', 100);
            $table->string('correo_institucional', 80);
            $table->string('pwd_institucional', 80);
        });

        Schema::create('Formulario', function (Blueprint $table) {
            $table->id();
            $table->string('formulario', 40);
            $table->text('descripcion')->nullable();
            $table->string('ruta', 40)->nullable();
            $table->timestamps();
        });

        Schema::create('Modulo', function (Blueprint $table) {
            $table->id();
            $table->string('modulo', 40);
            $table->text('descripcion')->nullable();
            $table->text('icono')->nullable();
            $table->timestamps();
        });

        Schema::create('Rol', function (Blueprint $table) {
            $table->id();
            $table->string('rol', 40)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('Sucursal', function (Blueprint $table) {
            $table->id();
            $table->string('sucursal', 40);
            $table->string('empresa', 40);
            $table->string('responsable', 40);
            $table->string('direccion', 80);
            $table->string('longitud', 40)->nullable();
            $table->string('latitud', 40)->nullable();
            $table->string('telefono', 10)->nullable();
            $table->string('celular', 10)->nullable();
            $table->string('email', 40)->nullable();
            $table->string('pais', 20);
            $table->string('ciudad', 20);
            $table->string('localidad', 30)->nullable();
            $table->string('imagen', 255)->nullable();
            $table->string('qr', 255)->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });

        Schema::create('User', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 40)->unique();
            $table->string('password', 80);
            $table->string('ci', 12)->unique();
            $table->string('nombres', 40);
            $table->string('apellido_paterno', 50)->nullable();
            $table->string('apellido_materno', 50)->nullable();
            $table->enum('genero', ['masculino', 'femenino']);
            $table->date('fecha_nac');
            $table->string('email', 80)->nullable();
            $table->string('telefono', 10)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('direccion', 50)->nullable();
            $table->enum('expedido', ['lpz', 'cbba', 'or', 'pt', 'tj', 'scz', 'bn', 'pd', 'ch', 'qr', 'ext'])->nullable();
            $table->text('codigo_qr')->nullable();
            $table->string('verificacion', 40)->nullable();
            $table->string('foto', 80)->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });

        // --- TABLAS PIVOTE (RELACIONES) ---

        Schema::create('FormularioModulo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_modulo')->constrained('Modulo')->onDelete('cascade');
            $table->foreignId('id_formulario')->constrained('Formulario')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['id_modulo', 'id_formulario']);
        });

        Schema::create('FormularioPermiso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rol')->constrained('Rol')->onDelete('cascade');
            $table->foreignId('id_modulo')->constrained('Modulo')->onDelete('cascade');
            $table->foreignId('id_formulario')->constrained('Formulario')->onDelete('cascade');
            $table->tinyInteger('puede_crear')->default(0);
            $table->tinyInteger('puede_leer')->default(0);
            $table->tinyInteger('puede_editar')->default(0);
            $table->tinyInteger('puede_eliminar')->default(0);
            $table->timestamps();
            $table->unique(['id_rol', 'id_modulo', 'id_formulario']);
        });

        Schema::create('ModuloRol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rol')->constrained('Rol')->onDelete('cascade');
            $table->foreignId('id_modulo')->constrained('Modulo')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['id_rol', 'id_modulo']);
        });

        Schema::create('UserRol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('User')->onDelete('cascade');
            $table->foreignId('id_rol')->constrained('Rol')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['id_user', 'id_rol']);
        });

        Schema::create('UserSucursal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('User')->onDelete('cascade');
            $table->foreignId('id_sucursal')->constrained('Sucursal')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['id_user', 'id_sucursal']);
        });

        // --- TABLA SANCTUM (PERSONAL ACCESS TOKENS) ---
        // Sanctum requiere esta tabla con este nombre exacto (nombre interno del paquete).

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // --- TABLA DE CÓDIGOS DE RESTABLECIMIENTO DE CONTRASEÑA ---
        Schema::create('password_reset_codes', function (Blueprint $table) {
            $table->id();
            $table->string('correo');
            $table->string('code', 6);
            $table->timestamp('expires_at');
            $table->boolean('used')->default(false);
            $table->timestamps();
            $table->index('correo');
            $table->index('code');
        });

        // ============================================================
        // --- DATOS INICIALES (SEED) ---
        // ============================================================

        // --- 1. ROL ADMINISTRADOR ---
        // Único rol de la plantilla base. Agregar otros roles según el proyecto.
        DB::table('Rol')->insert([
            'id'          => 1,
            'rol'         => 'Administrador',
            'descripcion' => 'Acceso total al sistema. Gestiona usuarios, roles, módulos, formularios y configuraciones generales.',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // --- 2. MÓDULO "Configuraciones" ---
        $idModulo = DB::table('Modulo')->insertGetId([
            'modulo'      => 'Configuraciones',
            'descripcion' => 'Gestión de módulos, formularios, permisos por rol y ajustes generales del sistema.',
            'icono'       => 'settings',
            'created_at'  => '2026-05-18 10:47:37',
            'updated_at'  => '2026-05-21 22:51:39',
        ]);

        // --- 3. FORMULARIO "Configuracion" ---
        $idFormulario = DB::table('Formulario')->insertGetId([
            'formulario'  => 'Configuracion',
            'descripcion' => 'Configuracion de modulos',
            'ruta'        => '/configuraciones',
            'created_at'  => '2026-05-24 21:23:08',
            'updated_at'  => '2026-05-26 14:17:22',
        ]);

        // --- 4. ASIGNAR FORMULARIO AL MÓDULO ---
        DB::table('FormularioModulo')->insert([
            'id_modulo'     => $idModulo,
            'id_formulario' => $idFormulario,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // --- 5. DAR ACCESO AL ROL "Administrador" (id=1) AL MÓDULO ---
        DB::table('ModuloRol')->insert([
            'id_rol'     => 1, // Administrador
            'id_modulo'  => $idModulo,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // --- 6. PERMISO COMPLETO AL ADMINISTRADOR SOBRE EL FORMULARIO ---
        DB::table('FormularioPermiso')->insert([
            'id_rol'         => 1, // Administrador
            'id_modulo'      => $idModulo,
            'id_formulario'  => $idFormulario,
            'puede_crear'    => 1,
            'puede_leer'     => 1,
            'puede_editar'   => 1,
            'puede_eliminar' => 1,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // --- 7. USUARIO SUPER ADMIN ---
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

        // --- 8. ASIGNAR ROL ADMINISTRADOR AL SUPER ADMIN ---
        DB::table('UserRol')->insert([
            'id_user'    => $userId,
            'id_rol'     => 1, // Administrador
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Revertir las migraciones.
     * El orden de eliminación es inverso al de creación.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_codes');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('UserSucursal');
        Schema::dropIfExists('UserRol');
        Schema::dropIfExists('ModuloRol');
        Schema::dropIfExists('FormularioPermiso');
        Schema::dropIfExists('FormularioModulo');

        Schema::dropIfExists('User');
        Schema::dropIfExists('Sucursal');
        Schema::dropIfExists('Rol');
        Schema::dropIfExists('Modulo');
        Schema::dropIfExists('Formulario');
        Schema::dropIfExists('Empresa');
    }
};
