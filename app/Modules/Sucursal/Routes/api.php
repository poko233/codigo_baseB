<?php

use App\Modules\Empresa\Controllers\EmpresaController;
use App\Modules\Sucursal\Controllers\SucursalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('empresas')
    ->group(function () {

        Route::get('/', [EmpresaController::class, 'index'])
            ->middleware('permiso:Configuracion,Empresas,Ver');

        Route::get('/{idEmpresa}/sucursales', [SucursalController::class, 'porEmpresa'])
            ->middleware('permiso:Configuracion,Sucursales,Ver');
    });
