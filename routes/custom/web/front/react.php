<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\react\pos\IndexPosController;


Route::get('/test', [IndexPosController::class, 'index']);
