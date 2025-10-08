<?php
// laravel imports
use Illuminate\Support\Facades\Route;

// Frontend Controllers
use App\Http\Controllers\front\HomeScreenController;




/*      Routes start    */

Route::get('/', [HomeScreenController::class, 'index'])->name('home');
Route::get('/CProduct/{productID}', [HomeScreenController::class, 'clientShowProductPage'])->name('clientShowProductPage');
Route::get('/sectionProducts/{sectionID}', [HomeScreenController::class, 'clientSectionProducts'])->name('sectionProducts');
Route::get('/userCart', [HomeScreenController::class, 'cart'])->name('cart');
