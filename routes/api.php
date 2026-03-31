<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CheckIpAddress;
use App\Http\Controllers\Api\SearchInvoiceController;
use App\Http\Controllers\Api\SearchTaxpayerController;
use App\Http\Controllers\Api\SearchTaxpayerTaxableController;
use App\Http\Controllers\Api\SyncInController;
use App\Http\Controllers\Api\SyncOutController;
use App\Http\Controllers\Api\SyncV1InvoicesController;
use App\Http\Controllers\Api\SyncV1PaymentsController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['throttle:api'])->group(function () {

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/v1/auth', [AuthController::class, 'auth']);

    Route::post('/v1/check', [CheckIpAddress::class, 'check']);
    Route::post('/v1/search/taxpayers', [SearchTaxpayerController::class, 'search']);
    Route::post('/v1/search/taxpayerstaxables', [SearchTaxpayerTaxableController::class, 'search']);
    Route::post('/v1/search/invoices', [SearchInvoiceController::class, 'search']);

    Route::middleware(['auth:sanctum', 'sync.ip'])->post('/v1/synchronisation/out', [SyncOutController::class, 'search']);
    Route::middleware(['auth:sanctum', 'sync.ip'])->post('/v1/synchronisation/in', [SyncInController::class, 'syncIn']);

    Route::middleware(['auth:sanctum', 'sync.ip'])->prefix('v1/sync')->group(function () {
        Route::get('/invoices', [SyncV1InvoicesController::class, 'index']);
        Route::get('/payments', [SyncV1PaymentsController::class, 'index']);
        Route::post('/payments', [SyncV1PaymentsController::class, 'store']);
    });

    Route::post('/v1/user/notifications', [NotificationController::class, 'notifications']);
    Route::post('/v1/user/notification/update', [NotificationController::class, 'updateNotification']);

    Route::middleware('auth:sanctum')->prefix('/v1')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });
});
