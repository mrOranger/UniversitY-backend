<?php

use App\Http\Controllers\Api\V1\DegreeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Degree Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Degree routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/

Route::resource('degrees', DegreeController::class)->except([
    'create', 'edit',
])->middleware(['auth:sanctum', 'role:admin,employee']);
