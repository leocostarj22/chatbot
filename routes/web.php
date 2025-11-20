<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\WidgetSettingController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Autenticação por sessão
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard protegido
Route::get('/admin', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('admin.dashboard');

// Grupo admin com sessão e gates
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Gestão de clientes e widget (somente admin)
    Route::middleware('can:manage-clients')->group(function () {
        Route::resource('clients', ClientController::class);
        Route::get('clients/{client}/settings', [WidgetSettingController::class, 'edit'])->name('clients.settings.edit');
        Route::put('clients/{client}/settings', [WidgetSettingController::class, 'update'])->name('clients.settings.update');
    });

    // Conversas (admin e operador)
    Route::middleware('can:access-conversations')->group(function () {
        Route::get('conversations', [ConversationController::class, 'index'])->name('conversations.index');
        Route::get('conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    });
});
