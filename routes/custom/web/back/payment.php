<?php
// laravel imports
use Illuminate\Support\Facades\Route;

//  Controllers
use App\Http\Controllers\Api\PaymentController;





// paymob
Route::POST('/payments/pay', [PaymentController::class, 'payWithPaymob']);
Route::match(['get', 'post'], '/payment/callback', [PaymentController::class, 'callback']);


// Route::get('/payment', [PaymentController::class, 'showPaymentForm']);
// Route::post('/payment', [PaymentController::class, 'processPayment']);


// Route::post('/checkout/response', function (Request $request) {
//     return $request->all();
// });
