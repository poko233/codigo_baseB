<?php

namespace App\Modules\Auth\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    private const TTL = 600; // 10 minutos

    private function cacheKey(int $idUser): string
    {
        return "permisos:user:{$idUser}";
    }

    private function sidebarKey(int $idRol): string
    {
        return "sidebar_rol_{$idRol}";
    }

    // ─── Permisos planos (para CheckPermission middleware) ────────────────────

    public function getPermisos($user): array
    {
        return Cache::remember(
            $this->cacheKey($user->id),
            self::TTL,
            fn () => $this->buildMapa($user->id)
        );
    }

    private function buildMapa(int $idUser): array
    {
        $rows = DB::table('user_rol')
            ->join('rol', 'rol.id', '=', 'user_rol.id_rol')
            ->join('formulario_permiso', 'formulario_permiso.id_rol', '=', 'rol.id')
            ->join('modulo', 'modulo.id', '=', 'formulario_permiso.id_modulo')
            ->join('formulario', 'formulario.id', '=', 'formulario_permiso.id_formulario')
            ->join('accion', 'accion.id', '=', 'formulario_permiso.id_accion')
            ->where('user_rol.id_user', $idUser)
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

    public function userHasPermission($user, string $modulo, string $formulario, string $accion): bool
    {
        $mapa = $this->getPermisos($user);

        return isset($mapa[$modulo][$formulario])
            && in_array($accion, $mapa[$modulo][$formulario], true);
    }

    // ─── Invalidación de caché ────────────────────────────────────────────────

    public function forgetPermisos(int $idUser): void
    {
        Cache::forget($this->cacheKey($idUser));
    }

    public function forgetPermisosDeRol(int $idRol): void
    {
        $usuarios = DB::table('user_rol')
            ->where('id_rol', $idRol)
            ->pluck('id_user');

        foreach ($usuarios as $idUser) {
            Cache::forget($this->cacheKey($idUser));
        }

        Cache::forget($this->sidebarKey($idRol));
    }

    // ─── Sidebar ──────────────────────────────────────────────────────────────

    public function getSidebar($user): array
    {
        $roles = DB::table('user_rol')
            ->join('rol', 'rol.id', '=', 'user_rol.id_rol')
            ->where('user_rol.id_user', $user->id)
            ->where('rol.estado', 'Activo')
            ->pluck('rol.id');

        $merged = [];

        foreach ($roles as $idRol) {
            $rolSidebar = Cache::remember(
                $this->sidebarKey($idRol),
                self::TTL,
                fn () => $this->buildSidebarParaRol($idRol)
            );

            foreach ($rolSidebar as $idModulo => $modulo) {
                if (!isset($merged[$idModulo])) {
                    $merged[$idModulo] = $modulo;
                } else {
                    foreach ($modulo['formularios'] as $idFormulario => $formulario) {
                        if (!isset($merged[$idModulo]['formularios'][$idFormulario])) {
                            $merged[$idModulo]['formularios'][$idFormulario] = $formulario;
                        } else {
                            $merged[$idModulo]['formularios'][$idFormulario]['acciones'] = array_values(
                                array_unique(array_merge(
                                    $merged[$idModulo]['formularios'][$idFormulario]['acciones'],
                                    $formulario['acciones']
                                ))
                            );
                        }
                    }
                }
            }
        }

        return array_values(array_map(function ($modulo) {
            $modulo['formularios'] = array_values($modulo['formularios']);
            return $modulo;
        }, $merged));
    }

    private function buildSidebarParaRol(int $idRol): array
    {
        $rows = DB::table('formulario_permiso')
            ->join('modulo', 'modulo.id', '=', 'formulario_permiso.id_modulo')
            ->join('formulario', 'formulario.id', '=', 'formulario_permiso.id_formulario')
            ->join('accion', 'accion.id', '=', 'formulario_permiso.id_accion')
            ->where('formulario_permiso.id_rol', $idRol)
            ->where('modulo.estado', 'Activo')
            ->where('formulario.estado', 'Activo')
            ->select(
                'modulo.id as id_modulo',
                'modulo.modulo',
                'modulo.icono',
                'formulario.id as id_formulario',
                'formulario.formulario',
                'formulario.ruta',
                'accion.accion'
            )
            ->get();

        $sidebar = [];
        foreach ($rows as $row) {
            if (!isset($sidebar[$row->id_modulo])) {
                $sidebar[$row->id_modulo] = [
                    'id'         => $row->id_modulo,
                    'nombre'     => $row->modulo,
                    'icono'      => $row->icono,
                    'formularios' => [],
                ];
            }
            if (!isset($sidebar[$row->id_modulo]['formularios'][$row->id_formulario])) {
                $sidebar[$row->id_modulo]['formularios'][$row->id_formulario] = [
                    'id'      => $row->id_formulario,
                    'nombre'  => $row->formulario,
                    'ruta'    => $row->ruta,
                    'acciones' => [],
                ];
            }
            if (!in_array($row->accion, $sidebar[$row->id_modulo]['formularios'][$row->id_formulario]['acciones'], true)) {
                $sidebar[$row->id_modulo]['formularios'][$row->id_formulario]['acciones'][] = $row->accion;
            }
        }

        return $sidebar;
    }
}
