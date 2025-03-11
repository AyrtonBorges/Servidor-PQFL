<?php

use App\Http\Controllers\ProdutorController;
use Illuminate\Support\Facades\Route;

Route::get('/produtores', [ProdutorController::class, 'index']);
Route::get('/produtores/{id}', [ProdutorController::class, 'show']);
Route::post('/produtores', [ProdutorController::class, 'store']);
Route::put('/produtores/{id}', [ProdutorController::class, 'update']);
Route::delete('/produtores/{id}', [ProdutorController::class, 'destroy']);
