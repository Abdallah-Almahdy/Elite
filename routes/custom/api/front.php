<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\permissionsController;
use App\Http\Controllers\Api\PosController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\systemSettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\react\pos\IndexPosController;

Route::post('/check-customer', [IndexPosController::class, 'check']);
Route::post('/create-customer', [IndexPosController::class, 'createCustomer']);
Route::post('/create-order', [IndexPosController::class, 'createOrder']);


// pos screen
// scan barcode





Route::middleware('auth:web', 'web')->group(function () {


    Route::get('/getUserPermissions/{id}', [permissionsController::class, 'getUserPermissions']);


    Route::get('/userWarehouseSettings', [systemSettingsController::class, 'userWarehouseSettings']);
    Route::post('/updateUserWarehouseSettings', [systemSettingsController::class, 'updateUserWarehouseSettings']);
    Route::get('/invicePrintersUserSettings', [systemSettingsController::class, 'invicePrintersUserSettings']);
    Route::get('/sectionUserSettings', [systemSettingsController::class, 'sectionUserSettings']);
    Route::post('/updateSectionUserSettings', [systemSettingsController::class, 'updateSectionUserSettings']);


    // systemSettings
    Route::get('/sectionsPrinterSettings', [systemSettingsController::class, 'sectionsPrinterSettings']);
    Route::post('/updateSectionPrinterSettings', [systemSettingsController::class, 'updateSectionPrinterSettings']);

    Route::get('/invicePrintersSystemSettings', [systemSettingsController::class, 'invicePrintersSystemSettings']);
    Route::post('/updateInvicePrintersSettings', [systemSettingsController::class, 'updateInvicePrintersSettings']);
    Route::get('/inviceConfigSystemSettings', [InvoiceController::class, 'invoiceConfig']);


    Route::get('/getWarehouses', [systemSettingsController::class, 'getWarehouses']);
    Route::post('/updateDefaultWarehouse', [systemSettingsController::class, 'setDefaultWarehouse']);



    Route::get('/users', [AuthController::class, 'getUsersInfo']);
    Route::post('/specialRegister', [AuthController::class, 'speacialRegister']);
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::post('/shifts/close', [ShiftController::class, 'close']);

    Route::get('/permissions', [permissionsController::class, 'userPermissions']);
    Route::post('/permissions', [permissionsController::class, 'edit']);

    Route::get('/invice-config', [InvoiceController::class, 'userInvioceConfig']);
    Route::post('/invice-config', [InvoiceController::class, 'editInviceConfig']);
    Route::post('/check-password', [InvoiceController::class, 'checkPassword']);

    Route::get('/admins', [AuthController::class, 'getAdmins']);
});


Route::get('/sections', [ProductsController::class, 'GetAllSections']);
Route::get('sections/{id}/products', [ProductsController::class, 'get_products']);
Route::get('/product/searchByname', [ProductsController::class, 'searchByname']);
Route::get('/product/searchByBarcode', [ProductsController::class, 'searchByBarcode']);
