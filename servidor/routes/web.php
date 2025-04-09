<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SyncController;
use Livewire\Volt\Volt;
use App\Http\Controllers\PendenciaController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';

Route::prefix('/')->group(base_path('routes/produtor.php'));

Route::middleware('auth:sanctum')->post('/sync', [SyncController::class, 'sync']);



Route::get('/admin/pendencias', [PendenciaController::class, 'webIndex'])->name('pendencias.index');
Route::post('/admin/pendencias/{id}/aprovar', [PendenciaController::class, 'aprovar'])->name('pendencias.aprovar');
Route::post('/admin/pendencias/{id}/rejeitar', [PendenciaController::class, 'rejeitar'])->name('pendencias.rejeitar');

