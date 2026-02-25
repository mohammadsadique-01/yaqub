<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebitorController;
use App\Http\Controllers\DebitorSiteController;
use App\Http\Controllers\DrillingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get('/', [AuthController::class, 'login'])->name('login');

    Route::post('/login', [AuthController::class, 'submitLogin'])
        ->name('login.submit')
        ->middleware('throttle:5,1');

    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('forgot.password');

    Route::post('/forgot-password', [AuthController::class, 'submitForgotPassword'])
        ->name('forgot.password.submit');
});

Route::middleware(['guest', 'throttle:5,1'])->group(function () {

    Route::get('/otp/{userId}', [OtpController::class, 'otp'])->name('otp');

    Route::post('/otp/verify', [OtpController::class, 'submitOtp'])->name('otp.submit');

    Route::post('/otp/resend', [OtpController::class, 'resendOtp'])->name('otp.resend');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'profilePage'])->name('profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/financial-year/{id}/switch', [AuthController::class, 'financialyearswitch'])->name('financial-year.switch');

    Route::group(['prefix' => 'master/debitors', 'as' => 'debitor.'], function () {

        Route::get('/', [DebitorController::class, 'index'])->name('index');
        Route::post('/', [DebitorController::class, 'store'])->name('store');
        Route::get('/data', [DebitorController::class, 'getData'])->name('data');
        Route::get('/{debitor}/edit', [DebitorController::class, 'edit'])->name('edit');
        Route::put('/{debitor}', [DebitorController::class, 'update'])->name('update');
        Route::delete('/{debitor}', [DebitorController::class, 'destroy'])->name('destroy');
        Route::get('/{debitor}', [DebitorController::class, 'show'])->name('show');

        Route::get('/{debitor}/sites', [DebitorController::class, 'getSites'])->name('sites');

    });

    Route::group(['prefix' => 'master'], function () {
        Route::resource('items', ItemController::class);
    });

    Route::delete('/debitor-sites/{debitorSite}', [DebitorSiteController::class, 'destroy'])->name('debitor-sites.destroy');

    Route::get('/locations/list', [LocationController::class, 'list'])->name('locations.list');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

    Route::get('/villages/list', [LocationController::class, 'villages'])->name('villages.list');

    Route::resource('operators', OperatorController::class)->except(['show', 'create']);
    Route::prefix('operators')->name('operators.')->group(function () {
        Route::get('payment', [OperatorController::class, 'payment'])->name('payment');
    });

    Route::group(['prefix' => 'drilling', 'as' => 'drilling.'], function () {
        Route::get('/list', [DrillingController::class, 'index'])->name('index');
        Route::get('/create', [DrillingController::class, 'create'])->name('create');
        Route::post('/drilling/store', [DrillingController::class, 'store'])->name('store');
        Route::get('/data', [DrillingController::class, 'getData'])->name('data');
        Route::delete('/{drillingReport}', [DrillingController::class, 'destroy'])->name('destroy');
        Route::get('/{drillingReport}', [DrillingController::class, 'show'])->name('show');
        Route::get('/{drilling}/edit', [DrillingController::class, 'edit'])->name('edit');
        Route::put('/{drilling}', [DrillingController::class, 'update'])->name('update');
        Route::get('/filter/sites-by-debitors', [DrillingController::class, 'getSitesByDebitors'])->name('filterSiteBydebitor');
        Route::get('drilling/pdf', [DrillingController::class, 'pdf'])->name('drilling.pdf');

    });

    Route::group(['prefix' => 'invoice', 'as' => 'invoice.'], function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::post('/store', [InvoiceController::class, 'store'])->name('store');
    });

});
