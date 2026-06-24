<?php

namespace App\Modules\Sucursal\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Empresa\Models\Empresa;
use App\Modules\Sucursal\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function misSucursales(Request $request)
    {
        $user = $request->user();

        $sucursales = $user->sucursales()
            ->where('sucursal.estado', 'Activo')
            ->get(['sucursal.id', 'sucursal.sucursal', 'sucursal.direccion', 'sucursal.ciudad', 'sucursal.estado']);

        return response()->json([
            'data'    => $sucursales,
            'message' => 'Sucursales obtenidas correctamente',
        ]);
    }

    public function porEmpresa(Request $request, int $idEmpresa)
    {
        Empresa::findOrFail($idEmpresa);

        $sucursales = Sucursal::deEmpresa($idEmpresa)
            ->where('estado', 'Activo')
            ->get(['id', 'sucursal', 'direccion', 'ciudad', 'estado']);

        return response()->json([
            'data'    => $sucursales,
            'message' => 'Sucursales obtenidas correctamente',
        ]);
    }
}
