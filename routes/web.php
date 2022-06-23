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
    Route::get('order/{order}/set/{status}', [\App\Http\Controllers\OrderController::class, 'setStatus'])->name('order.set.status');
    Route::resource('order', \App\Http\Controllers\OrderController::class);
    Route::get('order/{order}/success', [\App\Http\Controllers\OrderController::class, 'orderSuccess'])->name('order.success');

    Route::get('invoice/{order}', [\App\Http\Controllers\InvoiceController::class, 'invoice'])->name('invoice');

    Route::controller(\App\Http\Controllers\CartController::class)->prefix('cart')->group(function () {
        Route::post('/add/{product}', 'addToCart')->name('cart.add');
        Route::get('/remove/{product}', 'removeFromCart')->name('cart.remove');
        Route::put('/update', 'update')->name('cart.update');
    });

    Route::get('user/settings', [\App\Http\Controllers\UserController::class, 'settings'])->name('user.settings');
    Route::post('user/settings/store', [\App\Http\Controllers\UserController::class, 'storeSettings'])->name('user.settings.store');

    Route::group(['middleware' => 'role:admin'], function () {
        Route::prefix('product')->controller(\App\Http\Controllers\ProductController::class)->group(function() {
            Route::name('product.')->group(function() {
                Route::get('/import', 'import')->name('import');
                Route::post('/parsecsv', 'parseCsv')->name('parsecsv');
                Route::get('/import/confirm', 'confirmCsv')->name('import.confirm');
                Route::get('/doimport', 'doImport')->name('doimport');
            });
        });

        Route::resource('product', \App\Http\Controllers\ProductController::class);

        Route::resource('user', \App\Http\Controllers\UserController::class);

        Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
        Route::put('/settings/update', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    });
});
