<?php

use Illuminate\Support\Facades\Route;


Route::get('/login', [\App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [\App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [\App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
