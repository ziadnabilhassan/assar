<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductWebController;
use App\Http\Controllers\Api\OrderCartController;
use App\Http\Controllers\Api\ShoppingCartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/', [HomeController::class, 'index']);
Route::get('/page/{id?}', [HomeController::class, 'page']);
Route::get('/contacts', [HomeController::class, 'contacts']);

Route::prefix('products')->group(function () {

    Route::get('/', [ProductWebController::class, 'products']);

    Route::get('/category-type/{id}', [ProductWebController::class, 'categoryType']);

    Route::get('/category/{id}', [ProductWebController::class, 'category']);

    Route::get('/gender/{id}', [ProductWebController::class, 'gender']);

    Route::get('/details/{id}', [ProductWebController::class, 'productDetails']);

    Route::get('/quick-view/{variantId}', [ProductWebController::class, 'quickView']);

    Route::get('/variants/{productId}/{colorId}', [
        ProductWebController::class,
        'getUniqueColorsByVariantSize'
    ]);
});

Route::get('/checkout', [OrderCartController::class, 'index']);

Route::post('/store-order', [
    OrderCartController::class,
    'storeOrder'
]);


Route::get('/cart', [
    ShoppingCartController::class,
    'index'
]);

Route::post('/add-to-cart', [
    ShoppingCartController::class,
    'addToCart'
]);

Route::post('/update-cart', [
    ShoppingCartController::class,
    'update'
]);

Route::post('/remove-from-cart', [
    ShoppingCartController::class,
    'remove'
]);
