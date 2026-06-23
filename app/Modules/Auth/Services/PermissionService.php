<?php

namespace App\Modules\Auth\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    private const TTL = 3600;

    private function cacheKey(int $idUser, int $idEmpresa): string
    {
        return "permisos:user:{$idUser}:empresa:{$idEmpresa}";
    }

    public function getPermisos($user, int $idEmpresa): array
    {
        return Cache::remember(
            $this->cacheKey($user->id, $idEmpresa),
            self::TTL,
            fn () => $this->buildMapa($user->id, $idEmpresa)
        );
    }

    private function buildMapa(int $idUser, int $idEmpresa): array
    {
        $rows = DB::table('user_rol')
            ->join('rol', 'rol.id', '=', 'user_rol.id_rol')
            ->join('formulario_permiso', 'formulario_permiso.id_rol', '=', 'rol.id')
            ->join('modulo', 'modulo.id', '=', 'formulario_permiso.id_modulo')
            ->join('formulario', 'formulario.id', '=', 'formulario_permiso.id_formulario')
            ->join('accion', 'accion.id', '=', 'formulario_permiso.id_accion')
            ->where('user_rol.id_user', $idUser)
            ->where('rol.id_empresa', $idEmpresa)
            ->where('rol.estado', 'Activo')
            ->select('modulo.modulo', 'formulario.formulario', 'accion.accion')
            ->get();

        $mapa = [];
        foreach ($rows as $row) {
            if (!isset($mapa[$row->modulo][$row->formulario])) {
                $mapa[$row->modulo][$row->formulario] = [];
            }
            if (!in_array($row->accion, $mapa[$row->modulo][$row->formulario], true)) {
                $mapa[$row->modulo][$row->formulario][] = $row->accion;
            }
        }

        return $mapa;
    }

    public function userHasPermission($user, string $modulo, string $formulario, string $accion, int $idEmpresa): bool
    {
        $mapa = $this->getPermisos($user, $idEmpresa);

        return isset($mapa[$modulo][$formulario])
            && in_array($accion, $mapa[$modulo][$formulario], true);
    }

    public function forgetPermisos(int $idUser, int $idEmpresa): void
    {
        Cache::forget($this->cacheKey($idUser, $idEmpresa));
    }

    public function forgetPermisosDeRol(int $idRol): void
    {
        $usuarios = DB::table('user_rol')
            ->join('rol', 'rol.id', '=', 'user_rol.id_rol')
            ->where('user_rol.id_rol', $idRol)
            ->select('user_rol.id_user', 'rol.id_empresa')
            ->get();

        foreach ($usuarios as $u) {
            Cache::forget($this->cacheKey($u->id_user, $u->id_empresa));
        }
    }

    public function getSidebar($user, int $idEmpresa): array
    {
        $mapa = $this->getPermisos($user, $idEmpresa);

        $accesos = [];
        foreach ($mapa as $moduloNombre => $formularios) {
            foreach ($formularios as $formularioNombre => $acciones) {
                if (in_array('Ver', $acciones, true)) {
                    $accesos[$moduloNombre][] = $formularioNombre;
                }
            }
        }

        if (empty($accesos)) {
            return [];
        }

        $rows = DB::table('modulo')
            ->join('formulario_modulo', 'formulario_modulo.id_modulo', '=', 'modulo.id')
            ->join('formulario', 'formulario.id', '=', 'formulario_modulo.id_formulario')
            ->where('modulo.id_empresa', $idEmpresa)
            ->where('modulo.estado', 'Activo')
            ->where('formulario.estado', 'Activo')
            ->whereIn('modulo.modulo', array_keys($accesos))
            ->select(
                'modulo.id as id_modulo',
                'modulo.modulo',
                'modulo.icono',
                'formulario.id as id_formulario',
                'formulario.formulario',
                'formulario.ruta'
            )
            ->get();

        $sidebar = [];
        foreach ($rows as $row) {
            if (!in_array($row->formulario, $accesos[$row->modulo] ?? [], true)) {
                continue;
            }
            if (!isset($sidebar[$row->id_modulo])) {
                $sidebar[$row->id_modulo] = [
                    'id'          => $row->id_modulo,
                    'modulo'      => $row->modulo,
                    'icono'       => $row->icono,
                    'formularios' => [],
                ];
            }
            $sidebar[$row->id_modulo]['formularios'][] = [
                'id'         => $row->id_formulario,
                'formulario' => $row->formulario,
                'ruta'       => $row->ruta,
            ];
        }

        return array_values($sidebar);
    }
}
