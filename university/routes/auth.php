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
Route::post('register', [AuthController::class, 'register']);
Route::patch('confirm/user/{user_id}/code/{confirmation_code}', [AuthController::class, 'confirmAccount'])->name('auth.confirmation');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
});
