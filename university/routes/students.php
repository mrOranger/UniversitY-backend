<?php

use App\Http\Controllers\Api\V1\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Student routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/

Route::middleware(['auth:sanctum', 'role:admin,employee'])->group(function () {
    Route::resource('students', StudentController::class)->except([ 'create', 'edit' ]);
    Route::prefix('students')
        ->controller(StudentController::class)
        ->name('students.')
        ->group(function () {
            Route::get('course/{course}', 'getStudentsByCourse')->name('get-students-by_course');
            Route::patch('{student}/course/{course}', 'assignCourse')->name('assign-course');
    });
});
