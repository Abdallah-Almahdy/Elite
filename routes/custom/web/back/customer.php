<?php
// laravel imports
use Illuminate\Support\Facades\Route;

// Auth

// Frontend Controllers
use App\Http\Controllers\Front\HomeScreenController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Other Controllers
use App\Http\Controllers\Auth\cusomerUserController;


// customer routes start

Route::get('/customerRegisterPage', [cusomerUserController::class, 'showRegistrationForm']);
Route::post('/customerRegister', [cusomerUserController::class, 'register'])->name('customerRegister');

Route::get('/customerLoginPage', [cusomerUserController::class, 'showLoginForm']);
Route::post('/customerLogin', [cusomerUserController::class, 'login'])->name('customerLogin');

Route::get('/support', [HomeScreenController::class, 'support']);
Route::post('/deactivate', [RegisteredUserController::class, 'deactivateAccount'])->name('user.deactivate');
Route::get('/delteAccount', [RegisteredUserController::class, 'showDeactivateForm'])->name('user.deactivate.form');

Route::get('/searchResult', function () {
    return view('livewire.search.searchResult');
})->name('searchResult');
