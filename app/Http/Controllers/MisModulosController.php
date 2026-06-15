<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MisModulosController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

        $roles = DB::table('UserRol')
            ->where('id_user', $user->id)
            ->pluck('id_rol'); 

        if ($roles->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no tiene roles asignados.',
            ], 403);
        }

        $modulos = DB::table('ModuloRol')
            ->join('Modulo', 'ModuloRol.id_modulo', '=', 'Modulo.id')
            ->select(
                'Modulo.id',
                'Modulo.modulo      as nombre',
                'Modulo.descripcion',
                'Modulo.icono',
            )
            ->whereIn('ModuloRol.id_rol', $roles) 
            ->distinct()                          
            ->orderBy('Modulo.modulo')
            ->get();

        $modulosConFormularios = $modulos->map(function ($modulo) {
            $formularios = DB::table('FormularioModulo')
                ->join('Formulario', 'FormularioModulo.id_formulario', '=', 'Formulario.id')
                ->select(
                    'Formulario.id',
                    'Formulario.formulario as nombre',
                    'Formulario.ruta',
                    'Formulario.descripcion',
                )
                ->where('FormularioModulo.id_modulo', $modulo->id)
                ->orderBy('Formulario.formulario')
                ->get();

            return [
                'id'          => $modulo->id,
                'nombre'      => $modulo->nombre,
                'descripcion' => $modulo->descripcion,
                'icono'       => $modulo->icono,
                'formularios' => $formularios,
            ];
        });

        return response()->json([
            'success' => true,
            'roles'   => $roles,        
            'modulos' => $modulosConFormularios,
        ]);
    }
}

