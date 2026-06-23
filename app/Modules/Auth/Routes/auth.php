<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Auth\Controllers\SidebarController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/me/permisos', [AuthController::class, 'mePermisos']);
    Route::get('/sidebar', [SidebarController::class, 'index']);
});