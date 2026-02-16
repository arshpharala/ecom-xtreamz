<?php

use App\Http\Controllers\API\PaypalController;
use App\Http\Controllers\API\TourasController;
use Illuminate\Support\Facades\Route;

Route::get('/paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
Route::get('/paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');
Route::post('/touras/webhook', [TourasController::class, 'webhook'])->name('touras.webhook');
