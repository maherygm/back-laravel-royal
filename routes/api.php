<?php

use App\Http\Controllers\Api\EvenementController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\PayerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

//routes payements
Route::post("Payement", [PayerController::class, "stripe"]);
Route::get("Payement", [PayerController::class, "hello"]);


Route::get('/checkout/success', [PayerController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [PayerController::class, 'cancel'])->name('checkout.cancel');


Route::resource('Client', ClientController::class);
Route::resource('Admin', AdminController::class);
Route::resource('Evenement', EvenementController::class);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
