<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\CheckoutController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('about-us', [HomeController::class, 'page'])->name('about-us');
Route::get('contact-us', [HomeController::class, 'page'])->name('contact-us');


Route::get('products', [ProductController::class, 'index'])->name('products');
Route::get('products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::resource('cart', CartController::class);


Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/checkout/guest', [CheckoutController::class, 'processGuestOrder'])->name('checkout.guest.process');

Route::get('/order-summary/{order}', [CheckoutController::class, 'thankYou'])->name('order.summary');


Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login');

Route::prefix('/customers')->name('customers.')->middleware('auth')->group(function () {

    Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('profile/tab/{tab}', [ProfileController::class, 'loadTab'])->name('profile.tab');

    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
});


Route::group(['middelware' => 'auth'], function () {
    Route::post('checkout/process', [CheckoutController::class, 'processAuthenticatedOrder'])->name('checkout.process');
});


Route::prefix('ajax/')->name('ajax.')->group(function () {
    Route::get('get-products', [ProductController::class, 'getProducts'])->name('get-products');
    Route::get('category/{category}/attributes', [ProductController::class, 'getCategoryAttributes'])->name('category.attributes');

    Route::get('/variants/resolve', [ProductController::class, 'resolve'])->name('variants.resolve');
});


require __DIR__ . '/auth.php';
