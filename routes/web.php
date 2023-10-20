<?php

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


Route::get('/index', function () {
    return view('pages.index');
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
