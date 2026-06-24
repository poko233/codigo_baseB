# CLAUDE.md — Backend Metasoft

## Stack

- **Laravel** (PHP) — API REST
- **PostgreSQL** — base de datos (usa `ilike` para búsquedas case-insensitive)
- **Sanctum** — autenticación por tokens
- **Cache** — driver configurable (Redis recomendado en prod), TTL estándar 600 s

## Estructura de módulos

```
app/Modules/<Nombre>/
├── Controllers/
├── Models/
├── Repositories/     ← toda query de DB va aquí
├── Requests/         ← validación de entrada
├── Resource/         ← transformación de salida (JsonResource)
├── Routes/
└── Services/         ← lógica de negocio, orquesta repo + caché
```

Las rutas de cada módulo se incluyen en `routes/api.php`.

## Módulos existentes

| Módulo | Prefijo de ruta | Descripción |
|--------|----------------|-------------|
| Auth | `/login`, `/sidebar` | Autenticación, sidebar, middleware de permisos |
| Rol | `/roles` | CRUD roles + sincronización de permisos |
| Permiso | `/permisos` | Alta/baja individual de permisos por rol |
| Modulo | `/modulos` | CRUD módulos + asignación de formularios |
| Formulario | `/formularios` | CRUD formularios + asignación a módulos |
| Accion | — | Catálogo de acciones (Ver, Crear, Editar, Eliminar) |
| Empresa | `/empresa` | Datos de empresa |
| Sucursal | `/sucursales` | CRUD sucursales |

## Tablas clave de RBAC

```
rol ──< formulario_permiso >── modulo
                        └──── formulario
                        └──── accion

user_rol   : id_user, id_rol
modulo_rol : id_rol, id_modulo          (módulos visibles del rol)
formulario_modulo : id_modulo, id_formulario
```

`formulario_permiso` es la tabla central de permisos: una fila = un rol puede ejecutar una acción sobre un formulario de un módulo.

## Caché — claves y TTL (600 s)

| Clave | Contenido | Invalida cuando |
|-------|-----------|----------------|
| `permisos:user:{id}` | mapa plano de permisos del usuario | cambio de rol/permiso del usuario |
| `sidebar_rol_{id}` | árbol de sidebar de un rol | sync de permisos del rol |
| `roles:con_permisos` | todos los roles con sus permisos | crear/actualizar/eliminar rol, sync permisos |

La invalidación centralizada vive en `PermissionService::forgetPermisosDeRol()`.

## Convenciones

### Eager loading
- **Nunca usar `whereHas` dentro de constraints de `with()`** — genera correlated subqueries O(n).
- Usar `whereIn` con pluck previo para filtrar relaciones en eager loading:
  ```php
  $idsActivos = Modulo::where('estado', 'Activo')->pluck('id');
  Rol::with(['permisos' => fn($q) => $q->whereIn('id_modulo', $idsActivos)])
  ```
- Siempre especificar columnas en relaciones de segundo nivel:
  ```php
  'permisos.modulo:id,modulo,icono'
  ```

### Resources
- Usar `whenLoaded()` para todas las relaciones — nunca acceder directamente a `$this->relacion` si puede no estar cargada.

### Caché en Services
- Los métodos de lectura costosos (lista completa sin paginación) usan `Cache::remember()`.
- Los métodos de mutación llaman `Cache::forget()` o `PermissionService::forgetPermisosDeRol()`.

### Paginación
- `GET /api/roles` → paginado (default 15, configurable con `?por_pagina=N`).
- `GET /api/roles/permisos` → sin paginar (devuelve todos los roles, protegido por caché).

## Middleware de permisos

```
Route::middleware(['auth:sanctum', 'sucursal'])
     ->middleware('permiso:Modulo,Formulario,Accion')
```

El middleware `permiso` verifica contra el mapa cacheado en `PermissionService::getPermisos()`.

## RBAC y bypass de Administrador

Los roles listados en `config/rbac.php → super_roles` tienen acceso total sin consultar `formulario_permiso`.
El bypass se evalúa por NOMBRE de rol (no por ID) y se cachea en `user_is_super:{id}` (TTL 600 s).

**Roles actuales con bypass:** `Administrador`, `Superadmin` (ambos en BD y en config).

**Para asignar el rol Administrador a un usuario en producción:**
```bash
php artisan rbac:admin {user_id}
php artisan cache:clear   # si el driver de caché no invalida por clave
```
El comando crea el rol si no existe, lo asigna sin duplicar, e invalida la caché del usuario.

**El nombre del rol debe coincidir exactamente** con la columna `rol.rol` en BD y con el valor en `config/rbac.php`. Si se renombra el rol en BD, hay que actualizar config también.

**Invalidación de `user_is_super`:** se limpia automáticamente desde `PermissionService::forgetPermisos(int $idUser)`. Si se implementa un endpoint para asignar/desasignar roles de usuarios, debe llamar a este método.

## Índices pendientes (migration sugerida, no aplicada)

```php
// Composite index en formulario_permiso para queries de eager loading con filtros
$table->index(['id_rol', 'id_modulo', 'id_formulario'], 'fp_rol_modulo_formulario_idx');
```

Beneficia la query: `WHERE id_rol IN (...) AND id_modulo IN (...) AND id_formulario IN (...)`.
