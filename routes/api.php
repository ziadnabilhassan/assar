<?php
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminOrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductWebController;
use App\Http\Controllers\Api\OrderCartController;
use App\Http\Controllers\Api\PromoCodeController;
use App\Http\Controllers\Api\ShoppingCartController;
use App\Http\Controllers\Api\WishlistController;
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
Route::get('/pages/{id?}', [HomeController::class, 'page']);
Route::get('/contacts', [HomeController::class, 'contacts']);

Route::prefix('designs')->group(function () {
    Route::get('/templates', [DesignController::class, 'templates']);
    Route::get('/stickers', [DesignController::class, 'stickers']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/saved', [DesignController::class, 'index']);
        Route::post('/saved', [DesignController::class, 'store']);
        Route::get('/saved/{savedDesign}', [DesignController::class, 'show']);
        Route::match(['put', 'patch'], '/saved/{savedDesign}', [DesignController::class, 'update']);
        Route::delete('/saved/{savedDesign}', [DesignController::class, 'destroy']);
    });
});

Route::prefix('products')->group(function () {

    Route::get('/', [ProductWebController::class, 'products']);

    Route::get('/category-type/{id}', [ProductWebController::class, 'categoryType']);

    Route::get('/category-types/{id}', [ProductWebController::class, 'categoryType']);

    Route::get('/category/{id}', [ProductWebController::class, 'category']);

    Route::get('/categories/{id}', [ProductWebController::class, 'category']);

    Route::get('/gender/{id}', [ProductWebController::class, 'gender']);

    Route::get('/details/{id}', [ProductWebController::class, 'productDetails']);

    Route::get('/quick-view/{variantId}', [ProductWebController::class, 'quickView']);

    Route::get('/variants/{productId}/{colorId}', [
        ProductWebController::class,
        'getUniqueColorsByVariantSize'
    ]);

    Route::get('/{productId}/colors/{colorId}/variants', [
        ProductWebController::class,
        'getUniqueColorsByVariantSize'
    ]);

    Route::get('/{id}', [ProductWebController::class, 'productDetails']);
});

Route::get('/checkout', [OrderCartController::class, 'index']);

Route::post('/promocodes/validate', [PromoCodeController::class, 'validateCode']);

Route::post('/store-order', [
    OrderCartController::class,
    'storeOrder'
]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'store']);
    Route::put('/cart/items/{cartItem}', [CartController::class, 'update']);
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear']);
    Route::post('/promocodes/apply', [PromoCodeController::class, 'applyToCart']);
    Route::post('/promo-codes/apply', [PromoCodeController::class, 'applyToCart']);
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{product_id}', [WishlistController::class, 'destroy']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/{order}/payment-proof', [OrderController::class, 'uploadPaymentProof']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminOrderController::class, 'dashboard']);
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::patch('/orders/{order}/payment-proof/status', [AdminOrderController::class, 'updatePaymentProofStatus']);
});

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
