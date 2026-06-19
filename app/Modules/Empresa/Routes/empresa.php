<?php

use App\Modules\Empresa\Controllers\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('empresa')
    ->group(function () {

        // Ver listado y detalle → permiso "Ver"
        Route::get('/', [EmpresaController::class, 'index'])
            ->middleware('permiso:empresa,Listado,Ver');

        Route::get('/{empresa}', [EmpresaController::class, 'show'])
            ->middleware('permiso:empresa,Detalle,Ver');

        // Crear → permiso "Crear"
        Route::post('/', [EmpresaController::class, 'store'])
            ->middleware('permiso:empresa,Listado,Crear');

        // Editar → permiso "Editar"
        Route::put('/{empresa}',   [EmpresaController::class, 'update'])
            ->middleware('permiso:empresa,Detalle,Editar');
        Route::patch('/{empresa}', [EmpresaController::class, 'update'])
            ->middleware('permiso:empresa,Detalle,Editar');

        // Eliminar → permiso "Eliminar"
        Route::delete('/{empresa}', [EmpresaController::class, 'destroy'])
            ->middleware('permiso:empresa,Detalle,Eliminar');
    });