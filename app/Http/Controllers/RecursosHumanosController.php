<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecursosHumanosController extends Controller
{
    public function usuarios()
    {
        try {
            $usuarios = User::with(['roles'])
                ->latest('id')
                ->get();

            return response()->json([
                'usuarios' => $usuarios,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error en RecursosHumanosController al cargar usuarios.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    public function actualizarUsuario(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'usuario' => [
                    'required',
                    'string',
                    'max:40',
                    Rule::unique('User', 'usuario')->ignore($user->id, 'id'),
                ],
                'ci' => [
                    'required',
                    'string',
                    'max:12',
                    Rule::unique('User', 'ci')->ignore($user->id, 'id'),
                ],
                'nombres' => 'required|string|max:40',
                'apellido_paterno' => 'required|string|max:50',
                'apellido_materno' => 'nullable|string|max:50',
                'genero' => 'required|in:masculino,femenino',
                'fecha_nac' => 'nullable|date',
                'email' => [
                    'nullable',
                    'email',
                    'max:80',
                    Rule::unique('User', 'email')->ignore($user->id, 'id'),
                ],
                'telefono' => 'nullable|string|max:10',
                'celular' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:50',
                'expedido' => 'nullable|in:lpz,cbba,or,pt,tj,scz,bn,pd,ch,qr,ext',
                'estado' => 'required|in:activo,inactivo',
            ]);

            $user->update($validated);

            $user->load(['roles']);

            return response()->json([
                'message' => 'Usuario actualizado correctamente.',
                'usuario' => $user,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error en RecursosHumanosController al actualizar usuario.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }
}