<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Teachers\StoreTeacherRequest;
use App\Http\Requests\V1\Teachers\UpdateTeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Teacher::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, string $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $teacher)
    {
        //
    }
}
