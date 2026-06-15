<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ModuloRolController extends Controller
{

    public function index(): JsonResponse
    {
        $asignaciones = DB::table('ModuloRol')
            ->join('Rol',    'ModuloRol.id_rol',    '=', 'Rol.id')
            ->join('Modulo', 'ModuloRol.id_modulo', '=', 'Modulo.id')
            ->select(
                'ModuloRol.id',
                'ModuloRol.id_rol',
                'ModuloRol.id_modulo',
                'Rol.rol           as nombre_rol',
                'Modulo.modulo     as nombre_modulo',
                'Modulo.icono      as icono_modulo',
                'Modulo.descripcion as descripcion_modulo',
                'ModuloRol.created_at'
            )
            ->orderBy('Rol.rol')
            ->orderBy('Modulo.modulo')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $asignaciones,
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id_rol'    => 'required|integer|exists:Rol,id',
                'id_modulo' => 'required|integer|exists:Modulo,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        $existe = DB::table('ModuloRol')
            ->where('id_rol',    $validated['id_rol'])
            ->where('id_modulo', $validated['id_modulo'])
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Este módulo ya está asignado a ese rol.',
            ], 409);
        }

        $id = DB::table('ModuloRol')->insertGetId([
            'id_rol'     => $validated['id_rol'],
            'id_modulo'  => $validated['id_modulo'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $registro = DB::table('ModuloRol')
            ->join('Rol',    'ModuloRol.id_rol',    '=', 'Rol.id')
            ->join('Modulo', 'ModuloRol.id_modulo', '=', 'Modulo.id')
            ->select(
                'ModuloRol.id',
                'ModuloRol.id_rol',
                'ModuloRol.id_modulo',
                'Rol.rol           as nombre_rol',
                'Modulo.modulo     as nombre_modulo',
                'Modulo.icono      as icono_modulo',
            )
            ->where('ModuloRol.id', $id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Módulo asignado al rol correctamente.',
            'data'    => $registro,
        ], 201);
    }


    public function destroy(int $id): JsonResponse
    {
        $existe = DB::table('ModuloRol')->where('id', $id)->exists();

        if (! $existe) {
            return response()->json([
                'success' => false,
                'message' => 'Asignación no encontrada.',
            ], 404);
        }

        DB::table('ModuloRol')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asignación eliminada correctamente.',
        ]);
    }

}