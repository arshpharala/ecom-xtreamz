<?php

use App\Http\Controllers\Admin\CMS\AttachmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CMS\SettingController;
use App\Http\Controllers\Admin\Catalog\ProductController;
use App\Http\Controllers\Admin\Catalog\CategoryController;
use App\Http\Controllers\Admin\Catalog\AttributeController;
use App\Http\Controllers\Admin\Catalog\BrandController;
use App\Http\Controllers\Admin\Catalog\ProductVariantController;
use App\Http\Controllers\Admin\CMS\LocaleController;
use App\Http\Controllers\Admin\CMS\PageController;
use App\Http\Controllers\Admin\CMS\TinyMCEController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['prefix' => '/catalog', 'as' => 'catalog.'], function () {

    Route::resource('categories', CategoryController::class);
    Route::delete('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::post('categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    Route::post('categories/bulk-restore', [CategoryController::class, 'bulkRestore'])->name('categories.bulk-restore');


    Route::resource('products', ProductController::class);
    Route::delete('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    Route::post('products/bulk-restore', [ProductController::class, 'bulkRestore'])->name('products.bulk-restore');

    Route::resource('product.variants', ProductVariantController::class);


    Route::resource('attributes', AttributeController::class);
    Route::delete('attributes/{attribute}/restore', [AttributeController::class, 'restore'])->name('attributes.restore');
    Route::post('attributes/bulk-delete', [AttributeController::class, 'bulkDelete'])->name('attributes.bulk-delete');
    Route::post('attributes/bulk-restore', [AttributeController::class, 'bulkRestore'])->name('attributes.bulk-restore');

    Route::resource('brands', BrandController::class);
    Route::delete('brands/{product}/restore', [BrandController::class, 'restore'])->name('brands.restore');
});


Route::group(['prefix' => '/cms', 'as' => 'cms.'], function () {
    Route::resource('attachments', AttachmentController::class);
    Route::resource('settings', SettingController::class);

    Route::resource('locales', LocaleController::class);
    Route::delete('locales/{product}/restore', [LocaleController::class, 'restore'])->name('locales.restore');


    Route::resource('pages', PageController::class);
    Route::delete('pages/{product}/restore', [PageController::class, 'restore'])->name('pages.restore');

    Route::post('upload/tinymce', [TinyMCEController::class, 'upload'])->name('upload.tinymce');
});
