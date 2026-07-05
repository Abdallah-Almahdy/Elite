<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

// auth
// Registration route


Route::post('/register',  [AuthController::class, 'register']);
Route::post('/reset_pass', [AuthController::class, 'reset_pass']);
Route::post('/login', [AuthController::class, 'login']);
// Login route
Route::group(['middleware' => 'auth:sanctum'], function ()
{
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('get_user_data', [AuthController::class, 'get_user_data']);
    Route::post('update_user_data', [AuthController::class, 'update_user_data']);
    Route::post('send_user_photo', [AuthController::class, 'send_user_photo']);
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/user/profile', [UserController::class, 'updateUserProfile']);
    Route::get('/user/addresses', [UserController::class, 'getUserAddresses']);
    Route::post('/user/addresses', [UserController::class, 'createUserAddresses']);
    Route::post('/user/addresses/{addressId}', [UserController::class, 'updateUserAddresses']);
    Route::delete('/user/addresses/{addressId}', [UserController::class, 'deleteUserAddresses']);
    Route::post('/user/addresses/{addressId}/default', [UserController::class, 'setDefaultAddress']);
});


// end auth


