<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
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




// User Module Routes Group
Route::controller(UserController::class)->group(function () {
    Route::get('/', 'loginForm')->name('loginForm');
    Route::get('registration', 'registrationForm')->name('registrationForm');
    Route::post('register', 'register')->name('register')->middleware('transaction');
    Route::post('login', 'login')->name('login');
    Route::get('forgotPassword', 'forgotPasswordForm')->name('forgotPasswordForm');
    Route::post('forgotPassword', 'forgotPassword')->name('forgotPassword');
    Route::get('resetPassword/{token}', 'resetPasswordForm')->name('resetPasswordForm');
    Route::post('resetPassword', 'resetPassword')->name('resetPassword');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('profile', 'profile')->name('profile')->middleware('auth');
        Route::get('logout', 'logout')->name('logout');
        Route::get('changePassword', 'changePasswordForm')->name('changePasswordForm');
        Route::post('changePassword', 'changePassword')->name('changePassword');
    });
});

// Add middleware for access this route logged user only
Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    // Account Module Routes Group
    Route::controller(AccountController::class)->group(function () {

        Route::prefix('account')->group(function () {
            Route::get('/', 'account')->name('account');
            Route::get('list', 'accountList')->name('account.list');
            Route::get('view', 'accountView')->name('account.view');
            Route::post('save', 'accountSave')->name('account.save');
            Route::post('edit', 'accountEdit')->name('account.edit');
            Route::post('delete', 'accountDelete')->name('account.delete');
            Route::post('addBalance', 'addBalance')->name('addBalance');
        });

        // Another Account Routes
        Route::get('anotherAccount', 'anotherAccount')->name('anotherAccount');
        Route::get('anotherAccountRequestList', 'anotherAccountRequestList')->name('anotherAccount.requestList');
        Route::post('findAnotherAccount', 'findAnotherAccount')->name('anotherAccount.find');
        Route::post('requestAnotherAccount', 'requestAnotherAccount')->name('anotherAccount.request');
        Route::post('approveRequest', 'approveRequest')->name('anotherAccount.approveRequest');
    });

    // Category Module Routes Group
    Route::controller(CategoryController::class)->prefix('category')->group(function () {
        Route::get('/', 'category')->name('category');
        Route::get('list', 'categoryList')->name('category.list');
        Route::post('save', 'categorySave')->name('category.save');
        Route::get('view', 'categoryView')->name('category.view');
        Route::post('edit', 'categoryEdit')->name('category.edit');
        Route::post('delete', 'categoryDelete')->name('category.delete');
    });

    // Transaction Module Routes Group
    Route::controller(TransactionController::class)->group(function () {
        Route::get('transaction/{id?}', 'transaction')->name('transaction');
        Route::get('transactionList', 'transactionList')->name('transaction.list');
        Route::post('transactionSave', 'transactionSave')->name('transaction.save');
        Route::get('transactionView', 'transactionView')->name('transaction.view');
        Route::post('transactionEdit', 'transactionEdit')->name('transaction.edit');
        Route::post('transactionDelete', 'transactionDelete')->name('transaction.delete');
        Route::post('accountBalance', 'accountBalance')->name('accountBalance');
    });
});
