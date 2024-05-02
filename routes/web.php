<?php

use App\Http\Controllers\Password;
use App\Http\Controllers\Geolocation;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Controllers\EreasController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\TownsController;
use App\Http\Controllers\YearsController;
use App\Http\Controllers\ZonesController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CantonsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TaxableController;
use App\Http\Controllers\CommunesController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\TaxLabelController;
use App\Http\Controllers\TaxpayerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CollectorDepositController;
use App\Http\Controllers\AccountantDepositController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\AccountantDepositOutrightController;
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

Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(EnsureIsAdmin::class)->post('/password/admin-reset', [Password::class, 'adminResetPassword'])
        ->name('password.admin.reset');

    Route::middleware(EnsureIsAdmin::class)->name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

    Route::resource('/taxpayers', TaxpayerController::class);
    Route::resource('/invoices', InvoiceController::class)->parameters([
        'invoices' => 'invoice:notDelivery?,s_date?,e_date?,startInvoiceId?,endInvoiceId?',
    ]);

    Route::resource('/recoveries', RecoveryController::class);

    Route::name('geolocation.')->group(function () {
        Route::get('/geolocation/taxpayers', [Geolocation::class, 'zones'])->name('taxpayers');
        Route::get('/geolocation/users', [Geolocation::class, 'users'])->name('users');
        Route::post('/geolocation/user', [Geolocation::class, 'setUserGeolocation'])->name('user');
        Route::post('/geolocation/zone', [Geolocation::class, 'setZoneGeolocation'])->name('zone');
    });

    Route::name('accounts.')->group(function () {
        Route::resource('/accounts/stock-requests', StockRequestController::class);
        Route::resource('/accounts/stock-transfers', StockTransferController::class);
        Route::resource('/accounts/collector-deposits', CollectorDepositController::class);
        // Route::resource('/accounts/collector-deposits/{id}', CollectorDepositController::class);
        Route::resource('/accounts/accountant-deposits-title', AccountantDepositController::class);
        Route::resource('/accounts/accountant-deposits-outright', AccountantDepositOutrightController::class);
        Route::resource('/accounts/ledgers', LedgerController::class);
    });

    Route::middleware(EnsureIsAdmin::class)->name('taxations.')->group(function () {
        Route::resource('/taxations/taxables', TaxableController::class);
        Route::resource('/taxations/taxlabels', TaxLabelController::class);
        Route::resource('/taxations/tickets', TicketController::class);
    });

    Route::middleware(EnsureIsAdmin::class)->name('administratives.')->group(function () {
        Route::resource('/administratives/cantons', CantonsController::class);
        Route::resource('/administratives/towns', TownsController::class);
        Route::resource('/administratives/ereas', EreasController::class);
        Route::resource('/administratives/zones', ZonesController::class);
    });

    Route::middleware(EnsureIsAdmin::class)->name('economics.')->group(function () {
        Route::resource('/economics/categories', CategoriesController::class);
        Route::resource('/economics/activities', ActivitiesController::class);
    });

    Route::middleware(EnsureIsAdmin::class)->name('economics.')->group(function () {
        Route::resource('/economics/categories', CategoriesController::class);
        Route::resource('/economics/activities', ActivitiesController::class);
    });

    Route::middleware(EnsureIsAdmin::class)->name('settings.')->group(function () {
        Route::resource('/years', YearsController::class);
        Route::resource('settings/communes', CommunesController::class);
        Route::get('/import/taxpayers', [TaxpayerController::class, 'showImportPage'])->name('import-view');
    });

    Route::get('/prints',   [PrintController::class, 'index'])->name("prints");
    Route::get('/generate-pdf/{data}/{type?}/{action?}', [PrintController::class, 'download'])->name("generatePdf");
    Route::middleware(EnsureIsAdmin::class)->post('/import/taxpayer', [TaxpayerController::class, 'import'])->name('import.process');
    Route::middleware(EnsureIsAdmin::class)->get('/import/taxpayer', [TaxpayerController::class, 'showImportPage'])->name('import-view');
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
