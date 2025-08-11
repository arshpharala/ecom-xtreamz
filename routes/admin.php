<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CMS\TagController;
use App\Http\Controllers\Admin\CMS\PageController;
use App\Http\Controllers\Admin\Auth\RoleController;
use App\Http\Controllers\Admin\CMS\EmailController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\AdminController;
use App\Http\Controllers\Admin\CMS\LocaleController;
use App\Http\Controllers\Admin\Auth\ModuleController;
use App\Http\Controllers\Admin\CMS\CountryController;
use App\Http\Controllers\Admin\CMS\SettingController;
use App\Http\Controllers\Admin\CMS\TinyMCEController;
use App\Http\Controllers\Admin\Sales\OrderController;
use App\Http\Controllers\Admin\CMS\CurrencyController;
use App\Http\Controllers\Admin\Catalog\BrandController;
use App\Http\Controllers\Admin\Catalog\OfferController;
use App\Http\Controllers\Admin\Catalog\CouponController;
use App\Http\Controllers\Admin\CMS\AttachmentController;
use App\Http\Controllers\Admin\CMS\EmailAdminController;
use App\Http\Controllers\Admin\Sales\CustomerController;
use App\Http\Controllers\Admin\Auth\PermissionController;
use App\Http\Controllers\Admin\Catalog\ProductController;
use App\Http\Controllers\Admin\Catalog\CategoryController;
use App\Http\Controllers\Admin\Catalog\AttributeController;
use App\Http\Controllers\Admin\CMS\PaymentGatewayController;
use App\Http\Controllers\Admin\Catalog\ProductVariantController;
use App\Http\Controllers\Admin\Catalog\ProductVariantOfferController;

Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
    Route::get('/',                     'dashboard')->name('dashboard');
    Route::get('/metrics',              'metrics')->name('dashboard.metrics');
    Route::get('/sales-chart',          'salesChart')->name('dashboard.sales-chart');
    Route::get('/recent-orders',        'recentOrders')->name('dashboard.recent-orders');
    Route::get('/top-products',         'topProducts')->name('dashboard.top-products');
    Route::get('/new-customers',        'newCustomers')->name('dashboard.new-customers');
    Route::get('/payment-methods',      'paymentMethodBreakdown')->name('dashboard.payment-methods');
});

Route::group(['prefix' => '/auth', 'as' => 'auth.'], function () {

    Route::resource('modules',                             ModuleController::class);
    Route::resource('permissions',                         PermissionController::class);
    Route::resource('roles',                               RoleController::class);
    Route::resource('admins',                              AdminController::class);
});

Route::group(['prefix' => '/catalog', 'as' => 'catalog.'], function () {

    Route::resource('categories',                           CategoryController::class);
    Route::delete('categories/{category}/restore',          [CategoryController::class, 'restore'])->name('categories.restore');
    Route::post('categories/bulk-delete',                   [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    Route::post('categories/bulk-restore',                  [CategoryController::class, 'bulkRestore'])->name('categories.bulk-restore');


    Route::resource('products',                             ProductController::class);
    Route::delete('products/{product}/restore',             [ProductController::class, 'restore'])->name('products.restore');
    Route::post('products/bulk-delete',                     [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    Route::post('products/bulk-restore',                    [ProductController::class, 'bulkRestore'])->name('products.bulk-restore');


    Route::resource('product.variants',                     ProductVariantController::class);

    Route::resource('product.variant.offers',               ProductVariantOfferController::class);


    Route::resource('attributes',                           AttributeController::class);
    Route::delete('attributes/{attribute}/restore',         [AttributeController::class, 'restore'])->name('attributes.restore');
    Route::post('attributes/bulk-delete',                   [AttributeController::class, 'bulkDelete'])->name('attributes.bulk-delete');
    Route::post('attributes/bulk-restore',                  [AttributeController::class, 'bulkRestore'])->name('attributes.bulk-restore');

    Route::resource('brands',                               BrandController::class);
    Route::delete('brands/{product}/restore',               [BrandController::class, 'restore'])->name('brands.restore');

    Route::resource('offers',                               OfferController::class);
    Route::delete('offers/{offer}/restore',                 [OfferController::class, 'restore'])->name('offers.restore');

    Route::resource('coupons',                              CouponController::class);
});


Route::group(['prefix' => '/sales', 'as' => 'sales.'], function () {

    Route::resource('orders',                               OrderController::class);
    Route::delete('orders/{order}/restore',                 [OrderController::class, 'restore'])->name('orders.restore');

    Route::resource('customers',                            CustomerController::class);
});


Route::group(['prefix' => '/cms', 'as' => 'cms.'], function () {

    Route::resource('attachments',                          AttachmentController::class);
    Route::resource('settings',                             SettingController::class);

    Route::resource('locales',                              LocaleController::class);
    Route::delete('locales/{locale}/restore',               [LocaleController::class, 'restore'])->name('locales.restore');

    Route::resource('currencies',                           CurrencyController::class);
    Route::delete('currencies/{currency}/restore',          [CurrencyController::class, 'restore'])->name('currencies.restore');

    Route::resource('countries',                            CountryController::class);
    Route::delete('countries/{country}/restore',            [CountryController::class, 'restore'])->name('countries.restore');

    Route::resource('pages',                                PageController::class);
    Route::delete('pages/{page}/restore',                   [PageController::class, 'restore'])->name('pages.restore');

    Route::resource('tags',                                 TagController::class);
    Route::delete('tags/{tag}/restore',                     [TagController::class, 'restore'])->name('tags.restore');

    Route::resource('payment-gateways',                     PaymentGatewayController::class)->only(['store']);

    Route::resource('emails',                               EmailController::class);
    Route::delete('emails/{email}/restore',                 [EmailController::class, 'restore'])->name('emails.restore');

    Route::resource('email.admins',                         EmailAdminController::class);

    Route::post('upload/tinymce',                           [TinyMCEController::class, 'upload'])->name('upload.tinymce');
});
