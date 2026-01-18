<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PosController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\react\pos\IndexPosController;

Route::post('/check-customer', [IndexPosController::class, 'check']);
Route::post('/create-customer', [IndexPosController::class, 'createCustomer']);
Route::post('/create-order', [IndexPosController::class, 'createOrder']);


// pos screen
// scan barcode
Route::get('/get-product-by-barcode/{barcode}', [PosController::class, 'getProductByBarcode']);
Route::get('/get-products-by-name/{name}', [PosController::class, 'getProductsByName']);








// شاشه الكاشير
Route::get('/GetAllProducts',[ProductsController::class,'GetAllProducts']);
Route::get('/users',[AuthController::class, 'getUsersInfo']);
Route::post('/specialRegister',[AuthController::class, 'speacialRegister']);
Route::post('/invoices', [InvoiceController::class, 'store']);
Route::get('/invoices', [InvoiceController::class, 'index']);
