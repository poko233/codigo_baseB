<?php

use App\Modules\Permiso\Controllers\PermisoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'sucursal'])
    ->prefix('permisos')
    ->group(function () {

        Route::get('/{idRol}', [PermisoController::class, 'index'])
            ->middleware('permiso:Configuracion,Roles,Ver');

        Route::post('/{idRol}', [PermisoController::class, 'addPermiso'])
            ->middleware('permiso:Configuracion,Roles,Editar');

        Route::post('/{idRol}/sync', [PermisoController::class, 'sync'])
            ->middleware('permiso:Configuracion,Roles,Editar');

        Route::delete('/{rolId}/{formularioId}/{accionId}', [PermisoController::class, 'destroy'])
            ->middleware('permiso:Configuracion,Roles,Editar');
    });
