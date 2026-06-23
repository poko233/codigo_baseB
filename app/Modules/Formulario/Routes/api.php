<?php

use App\Modules\Formulario\Controllers\FormularioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'sucursal'])->group(function () {

    Route::get('/formularios', [FormularioController::class, 'index'])
        ->middleware('permiso:Configuracion,Formularios,Ver');

    Route::post('/formularios', [FormularioController::class, 'store'])
        ->middleware('permiso:Configuracion,Formularios,Crear');

    Route::get('/formularios/{formulario}', [FormularioController::class, 'show'])
        ->middleware('permiso:Configuracion,Formularios,Ver');

    Route::put('/formularios/{formulario}', [FormularioController::class, 'update'])
        ->middleware('permiso:Configuracion,Formularios,Editar');

    Route::delete('/formularios/{formulario}', [FormularioController::class, 'destroy'])
        ->middleware('permiso:Configuracion,Formularios,Eliminar');
});
