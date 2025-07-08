<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\Cataloge\ProductController;
use App\Http\Controllers\Admin\Cataloge\CategoryController;
use App\Http\Controllers\Admin\Cataloge\AttributeController;
use App\Http\Controllers\Admin\Catalog\ProductVariantController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/catalog', 'as' => 'catalog.'], function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('attributes', AttributeController::class);
    Route::resource('attribute-families', CategoryController::class);

    Route::resource('products.variants', ProductVariantController::class)->only(['index', 'store']);
});


Route::resource('settings', SettingController::class);
