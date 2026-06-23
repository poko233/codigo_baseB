<?php

use App\Modules\Rol\Controllers\RolController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'sucursal'])
    ->prefix('roles')
    ->group(function () {

        Route::get('/', [RolController::class, 'index'])
            ->middleware('permiso:Configuracion,Roles,Ver');

        Route::post('/', [RolController::class, 'store'])
            ->middleware('permiso:Configuracion,Roles,Crear');

        Route::get('/permisos', [RolController::class, 'todosConPermisos'])
            ->middleware('permiso:Configuracion,Roles,Ver');

        Route::get('/{rol}', [RolController::class, 'show'])
            ->middleware('permiso:Configuracion,Roles,Ver');

        

        Route::put('/{rol}', [RolController::class, 'update'])
            ->middleware('permiso:Configuracion,Roles,Editar');

        Route::delete('/{rol}', [RolController::class, 'destroy'])
            ->middleware('permiso:Configuracion,Roles,Eliminar');

        


        Route::get('/{rol}/permisos', [RolController::class, 'getPermisos'])
            ->middleware('permiso:Configuracion,Roles,Ver');

        Route::put('/{rol}/permisos', [RolController::class, 'syncPermisos'])
            ->middleware('permiso:Configuracion,Roles,Editar');
    });
