<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\ProductController;

Route::middleware('guest')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('about-us', [HomeController::class, 'page'])->name('about-us');
    Route::get('contact-us', [HomeController::class, 'page'])->name('contact-us');


    Route::get('products', [ProductController::class, 'index'])->name('products');
    Route::get('products/{slug}', [ProductController::class, 'show'])->name('products.show');


    Route::get('login', [LoginController::class, 'create'])->name('login');
});

Route::prefix('ajax/')->name('ajax.')->group(function () {
    Route::get('get-products', [ProductController::class, 'getProducts'])->name('get-products');
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
