<?php

use App\Actions\SamplePermissionApi;
use App\Actions\SampleRoleApi;
use App\Actions\SampleUserApi;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CheckIpAddress;
use App\Http\Controllers\Api\SearchTaxpayersAndInvoiceAndTaxpayerTaxableController;
use App\Http\Controllers\Api\SearchInvoiceController;
use App\Http\Controllers\Api\SearchTaxLabelController;
use App\Http\Controllers\Api\SearchTaxpayerController;
use App\Http\Controllers\Api\SearchTaxpayerTaxableController;
use App\Http\Controllers\Api\SyncInController;
use App\Http\Controllers\Api\SyncOutController;
use App\Http\Controllers\Api\SyncV1InvoicesController;
use App\Http\Controllers\Api\SyncV1PaymentsController;
use App\Http\Controllers\Api\TaxpayerController;
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
    Route::Post('/v1/search/taxpayers', [SearchTaxpayerController::class, 'search']);
    Route::Post('/v1/search/taxpayerstaxables', [SearchTaxpayerTaxableController::class, 'search']);
    Route::Post('/v1/search/invoices', [SearchInvoiceController::class, 'search']);

    Route::middleware(['auth:sanctum', 'sync.ip'])->post('/v1/synchronisation/out', [SyncOutController::class, 'search']);
    Route::middleware(['auth:sanctum', 'sync.ip'])->post('/v1/synchronisation/in', [SyncInController::class, 'syncIn']);

    Route::middleware(['auth:sanctum', 'sync.ip'])->prefix('v1/sync')->group(function () {
        Route::get('/invoices', [SyncV1InvoicesController::class, 'index']);
        Route::get('/payments', [SyncV1PaymentsController::class, 'index']);
        Route::post('/payments', [SyncV1PaymentsController::class, 'store']);
    });

    Route::post('/v1/user/notifications', [NotificationController::class, 'notifications']);
    Route::post('/v1/user/notification/update', [NotificationController::class, 'updateNotification']);


    Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });

    Route::prefix('v1')->group(function () {
        Route::post('auth', [AuthController::class, 'auth']);

        Route::post('/check', [CheckIpAddress::class, 'check']);
        Route::Post('/search/taxpayers', [SearchTaxpayerController::class, 'search']);
        Route::Post('/search/taxpayerstaxables', [SearchTaxpayerTaxableController::class, 'search']);
        Route::Post('/search/invoices', [SearchInvoiceController::class, 'search']);

        Route::middleware('auth:sanctum')->post('/v1/synchronisation/out', [SyncOutController::class, 'search']);
        Route::middleware('auth:sanctum')->post('/v1/synchronisation/in', [SyncInController::class, 'syncIn']);

        Route::post('/user/notifications', [NotificationController::class, 'notifications']);
        Route::post('/user/notification/update', [NotificationController::class, 'updateNotification']);



//        Route::get('/users', function (Request $request) {
//            return app(SampleUserApi::class)->datatableList($request);
//        });
//
//        Route::post('/users-list', function (Request $request) {
//            return app(SampleUserApi::class)->datatableList($request);
//        });
//
//        Route::post('/users', function (Request $request) {
//            return app(SampleUserApi::class)->create($request);
//        });
//
//        Route::get('/users/{id}', function ($id) {
//            return app(SampleUserApi::class)->get($id);
//        });
//
//        Route::put('/users/{id}', function ($id, Request $request) {
//            return app(SampleUserApi::class)->update($id, $request);
//        });
//
//        Route::delete('/users/{id}', function ($id) {
//            return app(SampleUserApi::class)->delete($id);
//        });
//
//
//        Route::get('/roles', function (Request $request) {
//            return app(SampleRoleApi::class)->datatableList($request);
//        });
//
//        Route::post('/roles-list', function (Request $request) {
//            return app(SampleRoleApi::class)->datatableList($request);
//        });
//
//        Route::post('/roles', function (Request $request) {
//            return app(SampleRoleApi::class)->create($request);
//        });
//
//        Route::get('/roles/{id}', function ($id) {
//            return app(SampleRoleApi::class)->get($id);
//        });
//
//        Route::put('/roles/{id}', function ($id, Request $request) {
//            return app(SampleRoleApi::class)->update($id, $request);
//        });
//
//        Route::delete('/roles/{id}', function ($id) {
//            return app(SampleRoleApi::class)->delete($id);
//        });
//
//        Route::post('/roles/{id}/users', function (Request $request, $id) {
//            $request->merge(['id' => $id]);
//            return app(SampleRoleApi::class)->usersDatatableList($request);
//        });
//
//        Route::delete('/roles/{id}/users/{user_id}', function ($id, $user_id) {
//            return app(SampleRoleApi::class)->deleteUser($id, $user_id);
//        });



//        Route::get('/permissions', function (Request $request) {
//            return app(SamplePermissionApi::class)->datatableList($request);
//        });
//
//        Route::post('/permissions-list', function (Request $request) {
//            return app(SamplePermissionApi::class)->datatableList($request);
//        });
//
//        Route::post('/permissions', function (Request $request) {
//            return app(SamplePermissionApi::class)->create($request);
//        });
//
//        Route::get('/permissions/{id}', function ($id) {
//            return app(SamplePermissionApi::class)->get($id);
//        });
//
//        Route::put('/permissions/{id}', function ($id, Request $request) {
//            return app(SamplePermissionApi::class)->update($id, $request);
//        });
//
//        Route::delete('/permissions/{id}', function ($id) {
//            return app(SamplePermissionApi::class)->delete($id);
//        });


    });
});
