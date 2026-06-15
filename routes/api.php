<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\FormularioModuloController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ModuloRolController;
use App\Http\Controllers\MisModulosController;
use App\Http\Controllers\RecursosHumanosController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\QrController;

Route::post('/qr/debug-generate', [QrController::class, 'debugGenerate']);
Route::post('/qr/regenerate-all', [QrController::class, 'regenerateAll']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/password/forgot-email', [PasswordResetController::class, 'sendCode']);
Route::post('/password/verify-code', [PasswordResetController::class, 'verifyCode']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('mis-modulos', MisModulosController::class);

    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Roles y permisos
    |--------------------------------------------------------------------------
    */
    Route::apiResource('roles', RolController::class);

    Route::prefix('roles/{rol}/permisos')->group(function () {
        Route::get('/', [
            PermisoController::class,
            'index'
        ]);

        Route::post('/', [
            PermisoController::class,
            'store'
        ]);

        Route::put('/{permiso}', [
            PermisoController::class,
            'update'
        ]);

        Route::delete('/{permiso}', [
            PermisoController::class,
            'destroy'
        ]);

        Route::post('/sync', [
            PermisoController::class,
            'sync'
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Módulos
    |--------------------------------------------------------------------------
    */
    Route::get('modulos', [
        ModuloController::class,
        'index'
    ]);

    Route::post('modulos', [
        ModuloController::class,
        'store'
    ]);

    Route::get('modulos/{id}', [
        ModuloController::class,
        'show'
    ]);

    Route::put('modulos/{id}', [
        ModuloController::class,
        'update'
    ]);

    Route::delete('modulos/{id}', [
        ModuloController::class,
        'destroy'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Formularios
    |--------------------------------------------------------------------------
    */
    Route::get('formularios', [
        FormularioController::class,
        'index'
    ]);

    Route::post('formularios', [
        FormularioController::class,
        'store'
    ]);

    Route::get('formularios/{id}', [
        FormularioController::class,
        'show'
    ]);

    Route::put('formularios/{id}', [
        FormularioController::class,
        'update'
    ]);

    Route::delete('formularios/{id}', [
        FormularioController::class,
        'destroy'
    ]);

    Route::get('formulario-modulo', [
        FormularioModuloController::class,
        'index'
    ]);

    Route::post('formulario-modulo', [
        FormularioModuloController::class,
        'store'
    ]);

    Route::delete('formulario-modulo/{id}', [
        FormularioModuloController::class,
        'destroy'
    ]);

    Route::get('formulario-modulo/modulo/{id_modulo}', [
        FormularioModuloController::class,
        'porModulo'
    ]);

    Route::get('modulo-rol', [
        ModuloRolController::class,
        'index'
    ]);

    Route::post('modulo-rol', [
        ModuloRolController::class,
        'store'
    ]);

    Route::delete('modulo-rol/{id}', [
        ModuloRolController::class,
        'destroy'
    ]);
    /*
    |--------------------------------------------------------------------------
    | Recursos Humanos
    |--------------------------------------------------------------------------
    */
    Route::prefix('recursos-humanos')->group(function () {

        Route::get('/usuarios', [
            RecursosHumanosController::class,
            'usuarios'
        ]);

        Route::put('/usuarios/{id}', [
            RecursosHumanosController::class,
            'actualizarUsuario'
        ]);

    });

    Route::prefix('empresa')->group(function () {
        Route::get('/', [
            EmpresaController::class,
            'show'
        ]);

        Route::patch('/', [
            EmpresaController::class,
            'update'
        ]);
    });
});