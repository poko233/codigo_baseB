<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\Formulario;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FormularioModuloController extends Controller
{
    
    public function index(): JsonResponse
    {
        $asignaciones = DB::table('FormularioModulo')
            ->join('Modulo',     'FormularioModulo.id_modulo',     '=', 'Modulo.id')
            ->join('Formulario', 'FormularioModulo.id_formulario', '=', 'Formulario.id')
            ->select(
                'FormularioModulo.id',
                'FormularioModulo.id_modulo',
                'FormularioModulo.id_formulario',
                'Modulo.modulo      as nombre_modulo',
                'Modulo.icono       as icono_modulo',
                'Formulario.formulario as nombre_formulario',
                'Formulario.ruta    as ruta_formulario',
                'FormularioModulo.created_at'
            )
            ->orderBy('Modulo.modulo')
            ->orderBy('Formulario.formulario')
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
                'id_modulo'     => 'required|integer|exists:Modulo,id',
                'id_formulario' => 'required|integer|exists:Formulario,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        $existe = DB::table('FormularioModulo')
            ->where('id_modulo',     $validated['id_modulo'])
            ->where('id_formulario', $validated['id_formulario'])
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Esta asignación ya existe.',
            ], 409);
        }

        $id = DB::table('FormularioModulo')->insertGetId([
            'id_modulo'     => $validated['id_modulo'],
            'id_formulario' => $validated['id_formulario'],
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $registro = DB::table('FormularioModulo')
            ->join('Modulo',     'FormularioModulo.id_modulo',     '=', 'Modulo.id')
            ->join('Formulario', 'FormularioModulo.id_formulario', '=', 'Formulario.id')
            ->select(
                'FormularioModulo.id',
                'FormularioModulo.id_modulo',
                'FormularioModulo.id_formulario',
                'Modulo.modulo         as nombre_modulo',
                'Modulo.icono          as icono_modulo',
                'Formulario.formulario as nombre_formulario',
                'Formulario.ruta       as ruta_formulario',
                'FormularioModulo.created_at'
            )
            ->where('FormularioModulo.id', $id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Asignación creada correctamente.',
            'data'    => $registro,
        ], 201);
    }

    
    public function destroy(int $id): JsonResponse
    {
        $existe = DB::table('FormularioModulo')->where('id', $id)->exists();

        if (! $existe) {
            return response()->json([
                'success' => false,
                'message' => 'Asignación no encontrada.',
            ], 404);
        }

        DB::table('FormularioModulo')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asignación eliminada correctamente.',
        ]);
    }

    
    public function porModulo(int $idModulo): JsonResponse
    {
        $modulo = Modulo::find($idModulo);

        if (! $modulo) {
            return response()->json(['success' => false, 'message' => 'Módulo no encontrado.'], 404);
        }

        $formularios = DB::table('FormularioModulo')
            ->join('Formulario', 'FormularioModulo.id_formulario', '=', 'Formulario.id')
            ->select(
                'FormularioModulo.id',
                'FormularioModulo.id_formulario',
                'Formulario.formulario as nombre_formulario',
                'Formulario.ruta       as ruta_formulario',
                'FormularioModulo.created_at'
            )
            ->where('FormularioModulo.id_modulo', $idModulo)
            ->orderBy('Formulario.formulario')
            ->get();

        return response()->json([
            'success' => true,
            'modulo'  => $modulo->modulo,
            'data'    => $formularios,
        ]);
    }
}