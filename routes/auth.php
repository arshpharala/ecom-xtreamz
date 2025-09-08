<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\Profile\CardController;
use App\Http\Controllers\Web\Profile\AddressController;
use App\Http\Controllers\Web\Profile\WishlistController;
use App\Http\Controllers\Web\ProviderCallbackController;
use App\Http\Controllers\Web\ProviderRedirectController;

Route::middleware('guest')->group(function () {
    Route::get('login',                             [LoginController::class, 'create'])->name('login');
    Route::post('login',                            [LoginController::class, 'store'])->name('login');
    Route::post('register',                         [LoginController::class, 'register'])->name('register');
    Route::get('forgot-password',                   [LoginController::class, 'forgotPassword'])->name('password.request');
    Route::post('password/email',                   [LoginController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}',           [LoginController::class, 'resetPasswordForm'])->name('password.reset');
    Route::post('/reset-password',                  [LoginController::class, 'resetPassword'])->name('password.update');

    Route::get('auth/{provider}/login',             ProviderRedirectController::class)->name('auth.provider.login');
    Route::get('auth/{provider}/callback',          ProviderCallbackController::class)->name('auth.provider.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('logout',                           [LoginController::class, 'destroy'])->name('logout');
});

Route::prefix('/customers')->name('customers.')->middleware('auth')->group(function () {
    Route::get('profile',                           [ProfileController::class, 'profile'])->name('profile');
    Route::get('profile/tab/{tab}',                 [ProfileController::class, 'loadTab'])->name('profile.tab');
    Route::post('profile/update',                   [ProfileController::class, 'update'])->name('profile.update');
    Route::post('password/update',                  [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::post('card/store',                       [CardController::class, 'store'])->name('card.store');
    Route::delete('/card/{card}/delete',            [CardController::class, 'destroy'])->name('cart.delete');

    Route::resource('address',                      AddressController::class);
    Route::resource('wishlist',                     WishlistController::class);
});
