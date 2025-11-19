<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Controllers\Api\MessageController;

Route::get('/widget/{clientId}', [WidgetController::class, 'show']);
Route::get('/widget/key/{publicKey}', [WidgetController::class, 'showByKey']);

Route::get('/messages', [MessageController::class, 'index']);
Route::post('/messages', [MessageController::class, 'store']);