<?php

use App\Modules\Modulo\Controllers\ModuloController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'sucursal'])->group(function () {

    Route::get('/modulos', [ModuloController::class, 'index'])
        ->middleware('permiso:Configuracion,Modulos,Ver');

    Route::post('/modulos', [ModuloController::class, 'store'])
        ->middleware('permiso:Configuracion,Modulos,Crear');

    Route::get('/modulos/{modulo}', [ModuloController::class, 'show'])
        ->middleware('permiso:Configuracion,Modulos,Ver');

    Route::put('/modulos/{modulo}', [ModuloController::class, 'update'])
        ->middleware('permiso:Configuracion,Modulos,Editar');

    Route::delete('/modulos/{modulo}', [ModuloController::class, 'destroy'])
        ->middleware('permiso:Configuracion,Modulos,Eliminar');
});
