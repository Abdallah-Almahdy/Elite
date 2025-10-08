<?php
// laravel imports
use Illuminate\Support\Facades\Route;

//  Controllers
use App\Http\Controllers\Back\tools\PaymentController;




// paymob
Route::get('/payments/pay', [PaymentController::class, 'payWithPaymob']);
Route::get('/payments/verify/{payment?}', [PaymentController::class, 'verifyWithPaymob'])->name('payment-verify');


// Route::get('/payment', [PaymentController::class, 'showPaymentForm']);
// Route::post('/payment', [PaymentController::class, 'processPayment']);


// Route::post('/checkout/response', function (Request $request) {
//     return $request->all();
// });
