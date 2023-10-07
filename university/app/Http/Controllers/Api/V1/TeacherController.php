<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Teachers\StoreTeacherRequest;
use App\Http\Requests\V1\Teachers\UpdateTeacherRequest;
use App\Services\Http\Controllers\Api\V1\Teacher\TeacherServiceInterface;

class TeacherController extends Controller
{
    private TeacherServiceInterface $teacherServiceInterface;

    public function __construct(TeacherServiceInterface $teacherService)
    {
        $this->teacherServiceInterface = $teacherService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->teacherServiceInterface->getAll();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $teacher)
    {
        return $this->teacherServiceInterface->getById($teacher);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
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
        return $this->teacherServiceInterface->delete($teacher);
    }
}
