<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('products/search', [\App\Http\Controllers\Api\ProductSearchController::class, 'results'])->name('api.products.search');
Route::get('products/search', [\App\Http\Controllers\Api\ProductSearchController::class, 'results'])->name('api.products.search');
Route::get('sync/products', [\App\Http\Controllers\Api\Sync\ProductController::class, 'get'])->name('api.get.products');
