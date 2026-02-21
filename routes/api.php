<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Master Panel Public API (Client Scripts Connect Here)
Route::prefix('master')->middleware([\App\Http\Middleware\EnsureMasterApiSignature::class, 'throttle:master'])->group(function () {
    Route::post('/verify-license', [MasterApiController::class, 'verifyLicense']);
    Route::post('/check-update', [MasterApiController::class, 'checkUpdate']);
    Route::get('/announcements', [MasterApiController::class, 'announcements']);
});
