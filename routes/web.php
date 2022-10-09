<?php

use Illuminate\Support\Facades\Auth;
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
//Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'user.activated'], function () {
        Route::get('order/review', [\App\Http\Controllers\OrderController::class, 'review'])->name('order.review');
        Route::post('order/confirm', [\App\Http\Controllers\OrderController::class, 'confirm'])->name('order.confirm');
        Route::resource('order', \App\Http\Controllers\OrderController::class)->except(['show', 'destroy']);
        Route::get('order/{order}/success', [\App\Http\Controllers\OrderController::class, 'orderSuccess'])->name('order.success');

        Route::controller(\App\Http\Controllers\CartController::class)->prefix('cart')->group(function () {
            Route::post('/add/{product}', 'addToCart')->name('cart.add');
            Route::get('/remove/{product}', 'removeFromCart')->name('cart.remove');
            Route::put('/update', 'update')->name('cart.update');
        });
    });

    Route::get('order/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('order.show');
    Route::get('order/{order}/set/{status}', [\App\Http\Controllers\OrderController::class, 'setStatus'])->name('order.set.status');

    Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart');

    Route::get('invoice/{order}', [\App\Http\Controllers\InvoiceController::class, 'invoice'])->name('invoice');
    //Route::get('invoice/vat/{order}', [\App\Http\Controllers\InvoiceController::class, 'vatInvoice'])->name('vat.invoice');

    Route::get('/', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('home');

    Route::get('view/{type}', [\App\Http\Controllers\ListViewController::class, 'set'])->name('list.view.set');

    Route::get('user/settings', [\App\Http\Controllers\UserController::class, 'settings'])->name('user.settings');
    Route::post('user/settings/store', [\App\Http\Controllers\UserController::class, 'storeSettings'])->name('user.settings.store');

    Route::group(['middleware' => 'role:admin,super_admin,warehouse'], function () {
        Route::prefix('product')->controller(\App\Http\Controllers\ProductController::class)->group(function() {
            Route::name('product.')->group(function() {
                Route::get('/import', 'import')->name('import');
                Route::post('/parsecsv', 'parseCsv')->name('parsecsv');
                Route::get('/import/confirm', 'confirmCsv')->name('import.confirm');
                Route::get('/doimport', 'doImport')->name('doimport');
            });
        });

        Route::resource('product', \App\Http\Controllers\ProductController::class);

        Route::resource('warehouse', \App\Http\Controllers\WarehouseController::class);
    });

    Route::group(['middleware' => 'role:admin,super_admin'], function () {
        Route::get('payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments');
        Route::post('payments/{order}', [\App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');

        Route::get('user/{user}/orders', [\App\Http\Controllers\UserController::class, 'orders'])->name('user.orders');
        Route::get('user/{user}/items', [\App\Http\Controllers\UserController::class, 'items'])->name('user.items');
        Route::get('user/{user}/prices', [\App\Http\Controllers\UserController::class, 'prices'])->name('user.prices');
        Route::get('user/{user}/prices/set/{product}', [\App\Http\Controllers\UserController::class, 'setPrice'])->name('user.prices.set');
        Route::delete('user/{user}/prices/delete/{product}', [\App\Http\Controllers\UserController::class, 'deletePrice'])->name('user.prices.delete');
        Route::put('user/{user}/prices/assign/{product}', [\App\Http\Controllers\UserController::class, 'assignPrice'])->name('user.prices.assign');
        Route::get('user/{user}/activate', [\App\Http\Controllers\UserController::class, 'activate'])->name('user.activate');
        Route::get('user/{user}/deactivate', [\App\Http\Controllers\UserController::class, 'deactivate'])->name('user.deactivate');
        Route::get('user/act-as/{user}', [\App\Http\Controllers\UserController::class, 'actAs'])->name('user.act-as');
        Route::get('user/remove-acting', [\App\Http\Controllers\UserController::class, 'removeActing'])->name('user.remove-acting');
        // turi buti apacioj kitu kas yra su prefix user
        Route::resource('user', \App\Http\Controllers\UserController::class);
        Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
        Route::put('/settings/update', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    });

    Route::group(['middleware' => 'role:super_admin'], function() {
        Route::delete('order/{order}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('order.destroy');
        Route::get('order/{order}/edit', [\App\Http\Controllers\OrderController::class, 'edit'])->name('order.edit');
        Route::get('order-item/{item}/remove', [\App\Http\Controllers\OrderController::class, 'removeItem'])->name('order.item.remove');
        Route::get('order/{order}/add/{product}', [\App\Http\Controllers\OrderController::class, 'addItem'])->name('order.item.add');
        Route::post('order/{order}/update', [\App\Http\Controllers\OrderController::class, 'update'])->name('order.update');
    });

    Route::group(['middleware' => 'role:warehouse,super_admin'], function() {
        Route::post('order/{order}/shortage', [\App\Http\Controllers\OrderController::class, 'shortage'])->name('order.shortage');
    });
});
