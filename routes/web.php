<?php

use App\Http\Controllers\TaxLabelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TownsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TaxableController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TaxpayerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Apps\PermissionManagementController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/lang/{locale}', [LanguageController::class, 'setLocale'])->name('lang.setLocale');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

        Route::resource('/taxpayers', TaxpayerController::class);
        Route::resource('/invoices', InvoiceController::class);

    Route::name('settings.')->group(function () {
        Route::resource('/taxables', TaxableController::class);
        Route::resource('/taxlabels', TaxLabelController::class);
        Route::resource('/towns', TownsController::class);
        //Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

    // Route::name('user-management.')->group(function () {
    //     Route::resource('/user-management/users', UserManagementController::class);
    //     Route::resource('/user-management/roles', RoleManagementController::class);
    //     Route::resource('/user-management/permissions', PermissionManagementController::class);
    // });

});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
