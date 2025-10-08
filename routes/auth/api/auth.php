<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


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

});


// end auth


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
