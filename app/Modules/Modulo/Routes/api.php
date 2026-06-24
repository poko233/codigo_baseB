<?php

use App\Modules\Modulo\Controllers\FormularioModuloController;
use App\Modules\Modulo\Controllers\ModuloController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'sucursal'])->group(function () {

    // ── CRUD Módulos ───────────────────────────────────────────────────────────

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

    // ── Formularios asignados al módulo (formulario_modulo) ────────────────────

    Route::get('/modulos/{modulo}/formularios', [FormularioModuloController::class, 'show'])
        ->middleware('permiso:Configuracion,Modulos,Ver');

    Route::post('/modulos/{modulo}/formularios', [FormularioModuloController::class, 'sync'])
        ->middleware('permiso:Configuracion,Modulos,Editar');

    Route::delete('/modulos/{modulo}/formularios/{formulario}', [FormularioModuloController::class, 'destroy'])
        ->middleware('permiso:Configuracion,Modulos,Editar');
});
