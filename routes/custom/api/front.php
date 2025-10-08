<?php

use App\Http\Controllers\Api\PosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\react\pos\IndexPosController;

// Route::post('/check-customer', [IndexPosController::class, 'check']);
// Route::post('/create-customer', [IndexPosController::class, 'createCustomer']);
// Route::post('/create-order', [IndexPosController::class, 'createOrder']);


// pos screen
// scan barcode
Route::get('/get-product-by-barcode/{barcode}', [PosController::class, 'getProductByBarcode']);
Route::get('/get-products-by-name/{name}', [PosController::class, 'getProductsByName']);

Route::post('/invoices', [PosController::class, 'makeInvoice']);

// scan name
