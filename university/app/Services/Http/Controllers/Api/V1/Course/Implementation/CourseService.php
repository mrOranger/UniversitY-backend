<?php

namespace App\Services\Http\Controllers\Api\V1\Course\Implementation;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\V1\Courses\StoreCourseRequest;
use App\Http\Requests\V1\Courses\UpdateCourseRequest;
use App\Http\Resources\Collections\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\User;
use App\Services\Http\Controllers\Api\V1\Course\CourseServiceInterface;

final class CourseService implements CourseServiceInterface
{
    public function getAll() : CourseCollection
    {
        return new CourseCollection(Course::with('professor', 'professor.user')->get());
    }

    public function getById(string $courseId) : CourseResource
    {
        $course = Course::with('professor', 'professor.user')->find($courseId);
        if($course === null) {
            throw new ResourceNotFoundException('Course ' . $courseId . ' does not exist.');
        }
        return new CourseResource($course);
    }

    public function save(StoreCourseRequest $request) : CourseResource
    {
        $validatedRequest = $request->validated();

        $professor = User::where('first_name', '=', $validatedRequest['professor']['user']['first_name'])
            ->where('last_name', '=', $validatedRequest['professor']['user']['last_name'])
            ->where('email', '=', $validatedRequest['professor']['user']['email'])
            ->where('birth_date', '=', $validatedRequest['professor']['user']['birth_date'])
            ->first();

        if($professor === null) {
            throw new ResourceNotFoundException('Professor does not exist.');
        }

        $course = new Course([
            'name' => $validatedRequest['name'],
            'sector' => $validatedRequest['sector'],
            'starting_date' => $validatedRequest['starting_date'],
            'ending_date' => $validatedRequest['ending_date'],
            'cfu' => $validatedRequest['cfu']
        ]);

        $course->professor()->associate($professor->teacher()->get()->first());
        $course->save();

        return new CourseResource($course);
    }

    public function update (UpdateCourseRequest $request, string $id) : CourseResource
    {
        $validatedRequest = $request->validated();
        $course = Course::find($id);

        if ($course === null) {
            throw new ResourceNotFoundException('Course ' . $id . ' does not exist.');
        }

        $professor = User::where('first_name', '=', $validatedRequest['professor']['user']['first_name'])
            ->where('last_name', '=', $validatedRequest['professor']['user']['last_name'])
            ->where('email', '=', $validatedRequest['professor']['user']['email'])
            ->where('birth_date', '=', $validatedRequest['professor']['user']['birth_date'])
            ->first();

        if($professor === null) {
            throw new ResourceNotFoundException('Professor does not exist.');
        }

        $course->update([
            'name' => $validatedRequest['name'],
            'sector' => $validatedRequest['sector'],
            'starting_date' => $validatedRequest['starting_date'],
            'ending_date' => $validatedRequest['ending_date'],
            'cfu' => $validatedRequest['cfu']
        ]);

        $course->professor()->associate($professor->teacher()->get()->first());

        return new CourseResource($course);
    }

    public function delete (string $courseId) : CourseResource
    {
        $course = Course::with('professor', 'professor.user')->find($courseId);
        if($course === null) {
            throw new ResourceNotFoundException('Course ' . $courseId . ' does not exist.');
        }
        $course->delete();
        return new CourseResource($course);
    }
}
