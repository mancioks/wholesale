<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('home');
    Route::get('order/review', [\App\Http\Controllers\OrderController::class, 'review'])->name('order.review');
    Route::post('order/confirm', [\App\Http\Controllers\OrderController::class, 'confirm'])->name('order.confirm');
    Route::resource('order', \App\Http\Controllers\OrderController::class);

    Route::get('/cart/add/{product}', [\App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart/remove/{product}', [\App\Http\Controllers\CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::put('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');

    Route::group(['middleware' => 'role:admin'], function () {
        Route::resource('product', \App\Http\Controllers\ProductController::class);
        Route::resource('user', \App\Http\Controllers\UserController::class);
    });
});
