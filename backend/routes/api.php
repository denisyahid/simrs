<?php

use App\Http\Controllers\Auth\AuthCtrl;
use App\Http\Controllers\Bridging\NoAuthCtrl;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthCtrl::class)->group(function () {
    Route::post('biller/generate-token', 'getSignature2');
});
Route::middleware(['jwt.auth.external'])->group(function () {
    Route::controller(NoAuthCtrl::class)->group(function () {
        Route::get('biller/get-tagihan', 'getTagihan');
        Route::post('biller/post-tagihan', 'updateBayar');
        Route::post('biller/batal-bayar', 'batalBayar');
    });
});
