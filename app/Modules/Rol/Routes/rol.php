<?php

use App\Modules\Rol\Controllers\RolController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('rol')
    ->group(function () {

        Route::get('/', [RolController::class, 'index'])
            ->middleware('permiso:rol,Listado,Ver');

        Route::get('/{rol}', [RolController::class, 'show'])
            ->middleware('permiso:rol,Detalle,Ver');

        Route::post('/', [RolController::class, 'store'])
            ->middleware('permiso:rol,Listado,Crear');

        Route::put('/{rol}',   [RolController::class, 'update'])
            ->middleware('permiso:rol,Detalle,Editar');
        Route::patch('/{rol}', [RolController::class, 'update'])
            ->middleware('permiso:rol,Detalle,Editar');

        Route::put('/{rol}/permisos', [RolController::class, 'syncPermisos'])
            ->middleware('permiso:rol,Permisos,Editar');

        Route::delete('/{rol}', [RolController::class, 'destroy'])
            ->middleware('permiso:rol,Detalle,Eliminar');
    });