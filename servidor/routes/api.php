<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\AuthUsuariosController;
use App\Http\Controllers\PendenciaController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::post('/sync', [SyncController::class, 'sync']);
// });

Route::post('/usuarios/login', [AuthUsuariosController::class, 'login']);


Route::middleware('auth:sanctum')->get('/sync', [SyncController::class, 'sync']);
Route::middleware('auth:sanctum')->post('/sync', [SyncController::class, 'sync']);

// Route::get('/sync', [SyncController::class, 'sync']);

Route::prefix('pendencias')->group(function () {
    Route::get('/', [PendenciaController::class, 'index']);
    Route::post('/{id}/aprovar', [PendenciaController::class, 'aprovar']);
    Route::post('/{id}/rejeitar', [PendenciaController::class, 'rejeitar']);
});
