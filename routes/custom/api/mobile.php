<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BanaresController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\FavoritesController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\RatesController;
use App\Http\Controllers\Api\SectionsController;
use App\Http\Controllers\Api\PrompCodeController;
use App\Http\Controllers\Api\FilterController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\AboutUsController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;


Route::middleware('auth:sanctum')->group(function ()
{
    // Favorites
    Route::get('get_user_favorites', [FavoritesController::class, 'getUserFavorites']);
    Route::get('update_user_favorites', [FavoritesController::class, 'updateUserFavorites']);
    Route::get('check_is_favorite', [FavoritesController::class, 'checkIsFavorite']);

    // Orders
    Route::post('make_order', [OrdersController::class, 'createOrder']);
    Route::get('get_all_orders', [OrdersController::class, 'getAllOrders']);
    Route::get('cancel_order', [OrdersController::class, 'cancelOrder']);

    // promocode
    Route::post('check_promocode', [PrompCodeController::class, 'check_promocode']);


    //Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('customer/save-token', [NotificationController::class, 'saveCustomerToken']);
    Route::post('contact_us', ContactUsController::class);
    Route::get('get_delivery_price_by_userID', [DeliveryController::class, 'getDeliveryPriceByUserID']);


});

Route::post('/pay', [PaymentController::class, 'pay']);
Route::post('/callback', [PaymentController::class, 'callback']);


Route::get('products_best_sellers', [ProductsController::class, 'get_best_sellers']);
Route::get('products_offer_rate', [ProductsController::class, 'get_offer_rate']);

Route::get('products_search', [ProductsController::class, 'products_search']);

Route::get('products/{id}', [ProductsController::class, 'get_product']);
Route::get('product_info', [ProductsController::class, 'product_info']);
Route::get('products', [ProductsController::class, 'get_all_products']);
Route::get('products', [ProductsController::class, 'get_all_products_pagination']);
//Route::get('productsWithPagination', [ProductsController::class, 'get_all_products_pagination']);


Route::get('categories/{id}', [SectionsController::class, 'get_category']);
Route::get('categories', [SectionsController::class, 'get_all_categories']);
Route::get('get_sub_of_cat', [SectionsController::class, 'get_sub_of_cat']);

Route::get('category_products', [SectionsController::class, 'get_all_category_products']);


Route::post('testPhoto', [SectionsController::class, 'testPhoto']);

// contact_us

// rate
Route::post('rate', [RatesController::class, 'rate']);
Route::get('get_all_users_raiting', [RatesController::class, 'get_all_users_raiting']);

// fav



// banares
Route::get('get_banares', BanaresController::class);

// orders


// user data
Route::get('CompanyData', [AuthController::class, 'CompanyData']);





// delivery
Route::get('getAllDeliveryPlaces', [DeliveryController::class, 'getAllDeliveryPlaces']);


// filter
Route::get('filter', [FilterController::class, 'search']);

// Congig

Route::get('config', [ConfigController::class, 'check']);
//About Us

Route::get('about' , [AboutUsController::class, 'index']);








// Assigning middleware to group of routes
Route::middleware('auth:sanctum')->group(function () {
    // Add your protected API routes here
    // For example:
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
