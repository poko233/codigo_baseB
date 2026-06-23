<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EmpresaSeeder::class,          // 1. empresa base
            AccionSeeder::class,           // 2. Ver, Crear, Editar, Eliminar
            SucursalSeeder::class,         // 3. sucursal Central (depende de empresa)
            RolSeeder::class,              // 4. Superadmin, Administrador, Usuario (depende de empresa)
            ModuloSeeder::class,           // 5. Dashboard, Configuracion (depende de empresa)
            FormularioSeeder::class,       // 6. formularios base (depende de empresa)
            FormularioModuloSeeder::class, // 7. asocia formularios a módulos
            PermisosSeeder::class,         // 8. asigna permisos a roles (depende de todo lo anterior)
            UserSeeder::class,             // 9. usuario admin → Superadmin
        ]);
    }
}
