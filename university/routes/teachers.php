<?php

use App\Http\Controllers\Api\V1\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Teacher routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/

Route::resource('teachers', TeacherController::class)->except([
    'create', 'edit',
])->middleware(['auth:sanctum', 'role:admin,employee,professor']);
