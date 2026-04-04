<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\permissionsController;
use App\Http\Controllers\Api\PosController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\ShiftController ;
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
//Route::middleware('auth:web', 'web')->group(function (){

    Route::get('/sections', [ProductsController::class, 'GetAllSections']);
    Route::get('sections/{id}/products', [ProductsController::class, 'get_products']);
    Route::get('/product/searchByname',[ProductsController::class, 'searchByname']);
    Route::get('/product/searchByBarcode',[ProductsController::class, 'searchByBarcode']);
    Route::get('/users', [AuthController::class, 'getUsersInfo']);
    Route::post('/specialRegister', [AuthController::class, 'speacialRegister']);
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::post('/shifts/close', [ShiftController::class, 'close']);

    Route::get('/permissions', [permissionsController::class, 'userPermissions']);
    Route::post('/permissions', [permissionsController::class, 'edit']);

    Route::get('/invice-config', [InvoiceController::class, 'inviceConfig']);
    Route::post('/invice-config', [InvoiceController::class, 'editInviceConfig']);
    Route::post('/check-password', [InvoiceController::class, 'checkPassword']);

// });

