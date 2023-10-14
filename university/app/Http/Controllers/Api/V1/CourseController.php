<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Courses\StoreCourseRequest;
use App\Http\Requests\V1\Courses\UpdateCourseRequest;
use App\Services\Http\Controllers\Api\V1\Course\CourseServiceInterface;

class CourseController extends Controller
{
    private CourseServiceInterface $courseServiceInterface;

    public function __construct(CourseServiceInterface $courseServiceInterface)
    {
        $this->courseServiceInterface = $courseServiceInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->courseServiceInterface->getAll();
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id id of the Course to show.
     */
    public function show(string $id)
    {
        return $this->courseServiceInterface->getById($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        return $this->courseServiceInterface->save($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, string $id)
    {
        return $this->courseServiceInterface->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->courseServiceInterface->delete($id);
    }
}
