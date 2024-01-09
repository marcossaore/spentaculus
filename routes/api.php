<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpentController;
use App\Http\Requests\SignUpRequest;
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
// Route::middleware('auth:sanctum')->prefix('spents')->group(function () {
//     Route::post('/', [SpentController::class, 'store']);
//     Route::get('/', [SpentController::class, 'list']);
// });

Route::apiResource('spents', SpentController::class)->middleware('auth:sanctum');
// Route::post('/', 'SpentController@store');
// Route::get('spents/', 'SpentController@list');


// Route::middleware('auth:sanctum')->apiResource('spents', SpentController::class);


Route::prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signUp']);
    Route::post('/signin', [AuthController::class, 'signIn']);
});

