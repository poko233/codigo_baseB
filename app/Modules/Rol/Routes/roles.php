<?php

use App\Modules\Roles\Controllers\RolController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('roles')
    ->group(function () {

        Route::get('/', [RolController::class, 'index'])
            ->middleware('permiso:Roles,Listado,Ver');

        Route::get('/{rol}', [RolController::class, 'show'])
            ->middleware('permiso:Roles,Detalle,Ver');

        Route::post('/', [RolController::class, 'store'])
            ->middleware('permiso:Roles,Listado,Crear');

        Route::put('/{rol}', [RolController::class, 'update'])
            ->middleware('permiso:Roles,Detalle,Editar');
        Route::patch('/{rol}', [RolController::class, 'update'])
            ->middleware('permiso:Roles,Detalle,Editar');

        Route::delete('/{rol}', [RolController::class, 'destroy'])
            ->middleware('permiso:Roles,Detalle,Eliminar');
    });