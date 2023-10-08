<?php

namespace App\Services\Http\Controllers\Api\V1\Course\Implementation;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\Collections\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\Http\Controllers\Api\V1\Course\CourseServiceInterface;

final class CourseService implements CourseServiceInterface
{
    public function getAll() : CourseCollection
    {
        return new CourseCollection(Course::all());
    }

    public function getById(string $courseId) : CourseResource
    {
        $course = Course::find($courseId);
        if($course === null) {
            throw new ResourceNotFoundException('Course ' . $courseId . ' not found.');
        }
        return new CourseResource($course);
    }

    public function save(StoreCourseRequest $request) : CourseResource
    {
        return new CourseResource($request);
    }

    public function update (UpdateCourseRequest $request) : CourseResource
    {
        return new CourseResource($request);
    }

    public function delete (string $courseId) : CourseResource
    {
        $course = Course::find($courseId);
        if($course === null) {
            throw new ResourceNotFoundException('Course ' . $courseId . ' not found.');
        }
        return new CourseResource($course);
    }
}
