<?php

use App\Modules\Empresas\Controllers\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('empresas')
    ->group(function () {

        // Ver listado y detalle → permiso "Ver"
        Route::get('/', [EmpresaController::class, 'index'])
            ->middleware('permiso:Empresas,Listado,Ver');

        Route::get('/{empresa}', [EmpresaController::class, 'show'])
            ->middleware('permiso:Empresas,Detalle,Ver');

        // Crear → permiso "Crear"
        Route::post('/', [EmpresaController::class, 'store'])
            ->middleware('permiso:Empresas,Listado,Crear');

        // Editar → permiso "Editar"
        Route::put('/{empresa}',   [EmpresaController::class, 'update'])
            ->middleware('permiso:Empresas,Detalle,Editar');
        Route::patch('/{empresa}', [EmpresaController::class, 'update'])
            ->middleware('permiso:Empresas,Detalle,Editar');

        // Eliminar → permiso "Eliminar"
        Route::delete('/{empresa}', [EmpresaController::class, 'destroy'])
            ->middleware('permiso:Empresas,Detalle,Eliminar');
    });