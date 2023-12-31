<?php

namespace App\Services\Http\Controllers\Api\V1\Student\Implementation;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\V1\Students\StudentRequest;
use App\Http\Resources\Collections\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use App\Services\Http\Controllers\Api\V1\Student\StudentServiceInterface;

final class StudentService implements StudentServiceInterface
{
    final public function getAll(): StudentCollection
    {
        return new StudentCollection(Student::with(['degree', 'user', 'courses'])->get());
    }

    final public function getById(string $id): StudentResource
    {
        $student = Student::with(['degree', 'user', 'courses'])->find($id);
        if ($student === null) {
            throw new ResourceNotFoundException('Student '.$id.' does not exist.');
        }

        return new StudentResource($student);
    }

    final public function save(StudentRequest $studentRequest): StudentResource
    {
        $degree = Degree::where('name', '=', $studentRequest->degree['name'])
            ->where('code', '=', $studentRequest->degree['code'])
            ->where('course_type', '=', $studentRequest->degree['course_type'])
            ->first();

        $user = User::where('first_name', '=', $studentRequest->user['first_name'])
            ->where('last_name', '=', $studentRequest->user['last_name'])
            ->where('birth_date', '=', $studentRequest->user['birth_date'])
            ->where('email', '=', $studentRequest->user['email'])
            ->first();

        if ($user === null) {
            throw new ResourceNotFoundException('Associated user does not exists.');
        }

        if ($degree === null) {
            $degree = Degree::create($studentRequest->degree);
        }
        $student = new Student([
            'bachelor_final_mark' => $studentRequest->bachelor_final_mark,
            'master_final_mark' => $studentRequest->master_final_mark,
            'phd_final_mark' => $studentRequest->phd_final_mark,
            'outside_prescribed_time' => $studentRequest->outside_prescribed_time,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $student->degree()->associate($degree);
        $student->user()->associate($user);
        $student->save();

        return new StudentResource($student);
    }

    final public function update(StudentRequest $studentRequest, string $id): StudentResource
    {
        $student = Student::find($id);
        if ($student === null) {
            throw new ResourceNotFoundException('Student '.$id.' does not exist.');
        }
        $degree = Degree::where('name', '=', $studentRequest->degree['name'])
            ->where('code', '=', $studentRequest->degree['code'])
            ->where('course_type', '=', $studentRequest->degree['course_type'])
            ->first();

        $user = User::where('first_name', '=', $studentRequest->user['first_name'])
            ->where('last_name', '=', $studentRequest->user['last_name'])
            ->where('birth_date', '=', $studentRequest->user['birth_date'])
            ->where('email', '=', $studentRequest->user['email'])
            ->first();

        if ($user === null) {
            throw new ResourceNotFoundException('User does not exist.');
        }

        if ($degree === null) {
            $degree = Degree::create($studentRequest->degree);
        }
        $student->update([
            'bachelor_final_mark' => $studentRequest->bachelor_final_mark,
            'master_final_mark' => $studentRequest->master_final_mark,
            'phd_final_mark' => $studentRequest->phd_final_mark,
            'outside_prescribed_time' => $studentRequest->outside_prescribed_time,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);

        $student->degree()->associate($degree);
        $student->user()->associate($user);

        return new StudentResource($student);
    }

    final public function delete(string $id): StudentResource
    {
        $student = Student::with(['degree', 'user', 'courses'])->first();
        if ($student === null) {
            throw new ResourceNotFoundException('Student '.$id.' does not exist.');
        }
        $student->delete();

        return new StudentResource($student);
    }

    public function assignCourse(string $studentId, string $courseId): StudentResource
    {
        $student = Student::find($studentId);
        if ($student === null) {
            throw new ResourceNotFoundException('Student '.$studentId.' does not exist.');
        }

        $course = Course::find($courseId);
        if ($course === null) {
            throw new ResourceNotFoundException('Course '.$courseId.' does not exist.');
        }

        $student->courses()->attach($course);

        return new StudentResource(Student::with(['degree', 'user', 'courses'])->find($studentId));
    }

    public function getStudentsByCourse(string $courseId): StudentCollection
    {
        $course = Course::find($courseId);

        if ($course === null) {
            throw new ResourceNotFoundException('Course '.$courseId.' does not exist.');
        }

        $students = Student::with(['degree', 'user'])->whereHas('courses', function ($query) use ($courseId) {
            $query->where('id', '=', $courseId);
        })->get();

        return new StudentCollection($students);
    }
}
