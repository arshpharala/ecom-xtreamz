<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaypalController;

Route::get('/paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
Route::get('/paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');
