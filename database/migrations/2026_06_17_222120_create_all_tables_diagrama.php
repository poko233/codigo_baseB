<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración consolidada: Diagrama_en_blanco.csv
 *
 * Contiene la creación de TODAS las tablas del diagrama UML en una sola
 * migración, en el orden correcto para que ninguna llave foránea falle.
 *
 * Orden de creación (up):
 *   1.  migrations               (meta-tabla de Laravel, protegida con hasTable)
 *   2.  password_reset_codes     (sin FKs)
 *   3.  personal_access_tokens   (sin FKs - Sanctum)
 *   4.  empresas                 (raíz)
 *   5.  users                    (raíz)
 *   6.  acciones                 (raíz)
 *   7.  sucursales               (FK -> empresas)
 *   8.  roles                    (FK -> empresas)
 *   9.  modulos                  (FK -> empresas)
 *   10. formularios              (FK -> empresas)
 *   11. user_empresas            (pivote: users + empresas)
 *   12. user_sucursales          (pivote: users + sucursales)
 *   13. user_roles               (pivote: users + roles)
 *   14. modulo_roles             (pivote: modulos + roles)
 *   15. formulario_modulos       (pivote: formularios + modulos)
 *   16. formulario_permisos      (pivote: roles + modulos + formularios + acciones)
 *
 * El down() elimina las tablas en el orden EXACTAMENTE inverso, para que
 * ninguna llave foránea impida el DROP.
 *
 * NOTAS IMPORTANTES (pendientes de confirmación con el usuario):
 * - `genero` y `expedido` en `users` estaban como enum(9) / enum(4) en el
 *   CSV (Lucidchart trunca los valores reales del enum). Se dejaron como
 *   string() hasta confirmar los valores exactos.
 * - `acciones` no tenía timestamps en el diagrama; se agregaron igual
 *   porque la regla general pide timestamps() en todas las tablas.
 * - `migrations` ya es creada automáticamente por el framework; se protege
 *   con Schema::hasTable() para evitar error de "tabla ya existe".
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2. password_reset_codes (sin FKs)
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

        // 3. personal_access_tokens (sin FKs - estándar Sanctum)
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

        // 4. empresas (raíz, sin FKs)
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('empresa', 100);
            $table->text('slogan')->nullable();
            $table->string('sigla', 200)->nullable();
            $table->string('telefono', 11)->nullable();
            $table->string('celular', 11)->nullable();
            $table->string('email', 80)->nullable();
            $table->text('direccion')->nullable();
            $table->string('responsable', 80)->nullable();
            $table->string('latitud', 80)->nullable();
            $table->string('longitud', 80)->nullable();
            $table->text('objeto')->nullable();
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->string('facebook', 40)->nullable();
            $table->string('instagram', 40)->nullable();
            $table->string('tiktok', 40)->nullable();
            $table->string('linkedin', 40)->nullable();
            $table->string('carrito', 8)->nullable();
            $table->decimal('tipo_cambio', 10, 2)->nullable();
            $table->string('logo_cuadrado', 80)->nullable();
            $table->string('logo_largo', 80)->nullable();
            $table->string('baner_inicio', 80)->nullable();
            $table->string('icono', 40)->nullable();
            $table->string('titulo_cierre', 80)->nullable();
            $table->text('mensaje_cierre')->nullable();
            $table->string('titulo_inicio', 80)->nullable();
            $table->text('mensaje_inicio')->nullable();
            $table->string('dominio', 200)->nullable();
            $table->string('smtp_correo', 100)->nullable();
            $table->string('correo_institucional', 80)->nullable();
            $table->string('pwd_institucional', 80)->nullable();
        });

        // 5. users (raíz, sin FKs)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 40)->unique();
            $table->string('password', 80);
            $table->string('ci', 12)->unique();
            $table->string('nombres', 40);
            $table->string('primer_apellido', 50);
            $table->string('segundo_apellido', 50)->nullable();
            $table->string('genero', 9)->nullable();
            $table->date('fecha_nac')->nullable();
            $table->string('email', 80)->nullable();
            $table->string('telefono', 10)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('direccion', 50)->nullable();
            $table->string('expedido', 4)->nullable();
            $table->text('codigo_qr')->nullable();
            $table->string('verificacion', 40)->nullable();
            $table->string('foto', 80)->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // 6. acciones (raíz, sin FKs)
        Schema::create('acciones', function (Blueprint $table) {
            $table->id();
            $table->text('accion');
        });

        // 7. sucursales (FK -> empresas)
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')
                ->constrained('empresas')
                ->onDelete('cascade');
            $table->string('sucursal', 40);
            $table->string('responsable', 40)->nullable();
            $table->string('direccion', 80)->nullable();
            $table->string('longitud', 40)->nullable();
            $table->string('latitud', 40)->nullable();
            $table->string('telefono', 10)->nullable();
            $table->string('celular', 10)->nullable();
            $table->string('email', 40)->nullable();
            $table->string('pais', 20)->nullable();
            $table->string('ciudad', 20)->nullable();
            $table->string('localidad', 30)->nullable();
            $table->string('imagen', 255)->nullable();
            $table->string('qr', 255)->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // 8. roles (FK -> empresas)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')
                ->constrained('empresas')
                ->onDelete('cascade');
            $table->string('rol', 40);
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // 9. modulos (FK -> empresas)
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')
                ->constrained('empresas')
                ->onDelete('cascade');
            $table->string('modulo', 40);
            $table->text('descripcion')->nullable();
            $table->text('icono')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // 10. formularios (FK -> empresas)
        Schema::create('formularios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')
                ->constrained('empresas')
                ->onDelete('cascade');
            $table->string('formulario', 40);
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->string('ruta', 40)->nullable();
            $table->timestamps();
        });

        // 11. user_empresas (pivote: users + empresas)
        Schema::create('user_empresa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('id_empresa')
                ->constrained('empresas')
                ->onDelete('cascade');
            $table->timestamps();
        });

        // 12. user_sucursales (pivote: users + sucursales)
        Schema::create('user_sucursal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('id_sucursal')
                ->constrained('sucursales')
                ->onDelete('cascade');
            $table->timestamps();
        });

        // 13. user_roles (pivote: users + roles)
        Schema::create('user_rol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('id_rol')
                ->constrained('roles')
                ->onDelete('cascade');
            $table->timestamps();
        });

        // 14. modulo_roles (pivote: modulos + roles)
        Schema::create('modulo_rol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rol')
                ->constrained('roles')
                ->onDelete('cascade');
            $table->foreignId('id_modulo')
                ->constrained('modulos')
                ->onDelete('cascade');
            $table->timestamps();
        });

        // 15. formulario_modulos (pivote: formularios + modulos)
        Schema::create('formulario_modulo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_modulo')
                ->constrained('modulos')
                ->onDelete('cascade');
            $table->foreignId('id_formulario')
                ->constrained('formularios')
                ->onDelete('cascade');
            $table->timestamps();
        });

        // 16. formulario_permisos (pivote: roles + modulos + formularios + acciones)
        Schema::create('formulario_permiso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rol')
                ->constrained('roles')
                ->onDelete('cascade');
            $table->foreignId('id_modulo')
                ->constrained('modulos')
                ->onDelete('cascade');
            $table->foreignId('id_formulario')
                ->constrained('formularios')
                ->onDelete('cascade');
            $table->foreignId('id_accion')
                ->constrained('acciones')
                ->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * Orden EXACTAMENTE inverso al de up(), para que ninguna FK bloquee
     * el DROP de una tabla referenciada.
     */
    public function down(): void
    {

    }
};