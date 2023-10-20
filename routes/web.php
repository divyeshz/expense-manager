<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

// Account Module Routes Group
Route::controller(AccountController::class)->group(function(){
    Route::get('/accounts', 'accountsList')->name('accounts.list');
    Route::get('/accountsAdd', 'accountsAdd')->name('accounts.add');
});

// User Module Routes Group
Route::controller(UserController::class)->group(function(){
    Route::get('/', 'loginForm')->name('loginForm');
    Route::get('registration','registrationForm')->name('registrationForm');
    Route::post('register','register')->name('register');
    Route::post('login','login')->name('login');
    Route::get('logout','logout')->name('logout');
    Route::get('forgotPassword','forgotPasswordForm')->name('forgotPasswordForm');
    Route::post('forgotPassword','forgotPassword')->name('forgotPassword');
});
