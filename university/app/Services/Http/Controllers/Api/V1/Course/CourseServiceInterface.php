<?php

namespace App\Services\Http\Controllers\Api\V1\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\Collections\CourseCollection;
use App\Http\Resources\CourseResource;

interface CourseServiceInterface
{
    public function getAll() : CourseCollection;
    public function getById(string $courseId) : CourseResource;
    public function save(StoreCourseRequest $request) : CourseResource;
    public function update (UpdateCourseRequest $request, string $id) : CourseResource;
    public function delete (string $courseId) : CourseResource;
}
