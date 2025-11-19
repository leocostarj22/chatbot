<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Controllers\Api\MessageController;

Route::get('/widget/{clientId}', [WidgetController::class, 'show']);

Route::get('/messages', [MessageController::class, 'index']);
Route::post('/messages', [MessageController::class, 'store']);

// Futuro: auth de operadores (Sanctum/JWT), settings CRUD, etc.