<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\Catalog\ProductController;
use App\Http\Controllers\Admin\Catalog\CategoryController;
use App\Http\Controllers\Admin\Catalog\AttributeController;
use App\Http\Controllers\Admin\Catalog\ProductVariantController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/catalog', 'as' => 'catalog.'], function () {

    Route::resource('categories', CategoryController::class);
    Route::delete('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::post('categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    Route::post('categories/bulk-restore', [CategoryController::class, 'bulkRestore'])->name('categories.bulk-restore');



    Route::resource('products', ProductController::class);
    Route::delete('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    Route::post('products/bulk-restore', [ProductController::class, 'bulkRestore'])->name('products.bulk-restore');

    Route::resource('attribute-families', CategoryController::class);

    Route::resource('products.variants', ProductVariantController::class)->only(['index', 'store']);


    Route::resource('attributes', AttributeController::class);
    Route::delete('attributes/{attribute}/restore', [AttributeController::class, 'restore'])->name('attributes.restore');
    Route::post('attributes/bulk-delete', [AttributeController::class, 'bulkDelete'])->name('attributes.bulk-delete');
    Route::post('attributes/bulk-restore', [AttributeController::class, 'bulkRestore'])->name('attributes.bulk-restore');


    Route::get('category/{id}/attributes', [CategoryController::class, 'attributesJson'])->name('category.attributes');

});


Route::resource('settings', SettingController::class);
