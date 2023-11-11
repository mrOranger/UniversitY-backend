<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application's
| authentication. These routes are loaded by the RouteServiceProvider
| and all of them will be assigned to the "api" middleware group.
| Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::patch('confirm/user/{user_id}/code/{confirmation_code}', [AuthController::class, 'confirmAccount']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
});
