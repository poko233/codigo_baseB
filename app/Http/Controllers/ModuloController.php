<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ModuloController extends Controller
{

    public function index(): JsonResponse
    {
        $modulos = Modulo::with(['roles', 'formularios'])->get();

        return response()->json([
            'success' => true,
            'data'    => $modulos,
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'modulo'      => 'required|string|max:100|unique:modulo,modulo',
                'descripcion' => 'nullable|string|max:255',
                'icono'       => 'nullable|string|max:100',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        $modulo = Modulo::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Módulo creado correctamente.',
            'data'    => $modulo,
        ], 201);
    }


    public function show(int $id): JsonResponse
    {
        $modulo = Modulo::with(['roles', 'formularios'])->find($id);

        if (! $modulo) {
            return response()->json([
                'success' => false,
                'message' => 'Módulo no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $modulo,
        ]);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $modulo = Modulo::find($id);

        if (! $modulo) {
            return response()->json([
                'success' => false,
                'message' => 'Módulo no encontrado.',
            ], 404);
        }

        try {
            $validated = $request->validate([
                'modulo'      => 'sometimes|required|string|max:100|unique:modulo,modulo,' . $id,
                'descripcion' => 'nullable|string|max:255',
                'icono'       => 'nullable|string|max:100',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        $modulo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Módulo actualizado correctamente.',
            'data'    => $modulo,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $modulo = Modulo::find($id);

        if (! $modulo) {
            return response()->json([
                'success' => false,
                'message' => 'Módulo no encontrado.',
            ], 404);
        }

        $modulo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Módulo eliminado correctamente.',
        ]);
    }
}