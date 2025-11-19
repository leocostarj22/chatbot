<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\WidgetSettingController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->group(function () {
    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/settings', [WidgetSettingController::class, 'edit'])->name('clients.settings.edit');
    Route::put('clients/{client}/settings', [WidgetSettingController::class, 'update'])->name('clients.settings.update');
});
