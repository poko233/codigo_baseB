<?php

namespace App\Modules\Rol\Services;

use App\Modules\Rol\Models\Rol;
use App\Modules\Rol\Repositories\RolRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class RolService
{
    public function __construct(private RolRepository $repo) {}


    public function listar(array $filtros): LengthAwarePaginator
    {
        return $this->repo->paginar($filtros);
    }


    public function detalle(Rol $rol): Rol
    {
        return $this->repo->conPermisos($rol);
    }


    public function crear(array $datos): Rol
    {
        return $this->repo->crear([
            'id_empresa'  => $datos['id_empresa'],
            'rol'         => $datos['rol'],
            'descripcion' => $datos['descripcion'] ?? null,
            'estado'      => $datos['estado']       ?? 'Activo',
        ]);
    }


    public function actualizar(Rol $rol, array $datos): Rol
    {
        unset($datos['id_empresa']);
        return $this->repo->actualizar($rol, $datos);
    }

    /* ── Asignar / quitar permisos dinámicamente ─────────────── */
    /**
     * Recibe el array completo de permisos deseados y sincroniza.
     * Si envías [] quita todos los permisos.
     *
     * Ejemplo de payload:
     * {
     *   "permisos": [
     *     {"id_modulo": 1, "id_formulario": 3, "id_accion": 1},  // Ver
     *     {"id_modulo": 1, "id_formulario": 3, "id_accion": 2},  // Editar
     *   ]
     * }
     *
     * El cajero tendría Ver + Editar pero NO Eliminar → simplemente
     * no incluyes la acción Eliminar en el array.
     */
    public function sincronizarPermisos(Rol $rol, array $permisos): Rol
    {
        $this->repo->sincronizarPermisos($rol, $permisos);
        return $this->repo->conPermisos($rol);
    }

    public function eliminar(Rol $rol): void
    {
        if ($rol->user()->exists()) {
            abort(422, 'No se puede eliminar un rol que tiene usuarios asignados.');
        }
        $this->repo->eliminar($rol);
    }
}