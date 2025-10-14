<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CouponController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\Profile\AddressController;
use App\Http\Controllers\Web\ProviderCallbackController;
use App\Http\Controllers\Web\ProviderRedirectController;

Route::get('/',                                         [HomeController::class, 'index'])->name('home');
Route::get('about-us',                                  [HomeController::class, 'page'])->name('about-us');
Route::get('contact-us',                                [HomeController::class, 'page'])->name('contact-us');
Route::get('policy',                                    [HomeController::class, 'page'])->name('policy');

Route::get('clearance',                                 [ProductController::class, 'clearance'])->name('clearance');
Route::get('featured',                                  [ProductController::class, 'featured'])->name('featured');
Route::get('products',                                  [ProductController::class, 'index'])->name('products');
Route::get('products/{slug}',                           [ProductController::class, 'show'])->name('products.show');

Route::get('search',                                    [SearchController::class, 'search'])->name('search');

Route::resource('cart',                                 CartController::class);

Route::get('checkout',                                  [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('checkout',                                 [CheckoutController::class, 'processOrder'])->name('checkout');

Route::post('/paypal/create',                           [CheckoutController::class, 'processOrder'])->name('paypal.create');
Route::post('/paypal/capture',                          [CheckoutController::class, 'capturePaypalOrder'])->name('paypal.capture');

Route::post('/stripe/create-intent',                    [CheckoutController::class, 'createStripeIntent'])->name('stripe.create-intent');
Route::post('/stripe/confirm-payment',                  [CheckoutController::class, 'confirmStripePayment'])->name('stripe.confirm-payment');


Route::get('/order-summary/{order}',                    [CheckoutController::class, 'thankYou'])->name('order.summary');

Route::prefix('ajax/')->name('ajax.')->group(function () {
    Route::get('get-products',                          [ProductController::class, 'getProducts'])->name('get-products');
    Route::get('category/{category}/attributes',        [ProductController::class, 'getCategoryAttributes'])->name('category.attributes');
    Route::get('/variants/resolve',                     [ProductController::class, 'resolve'])->name('variants.resolve');

    Route::delete('/cart/clear-selected',     [CartController::class, 'clearSelected'])->name('cart.clear-selected');


    Route::get('cities/{province}',                     [AddressController::class, 'getCities'])->name('province.cities');
    Route::get('areas/{city}',                          [AddressController::class, 'getAreas'])->name('city.areas');

    Route::post('/coupon/apply',                        [CouponController::class, 'apply']);
    Route::post('/coupon/remove',                       [CouponController::class, 'remove']);

    Route::get('currencies',                            [HomeController::class, 'currencies']);

    Route::post('subscribe',                             [HomeController::class, 'subscribe'])->name('subscribe')->middleware('throttle:3,1');;
});


Route::get('email', function () {
    $order = \App\Models\Cart\Order::oldest()->first();
    return view('email.order-success', compact('order'));
});


Route::get('clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    return 'Cleared!';
});
