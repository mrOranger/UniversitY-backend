<?php

namespace App\Services\Http\Controllers\Api\V1\Teacher\Implementation;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\V1\Teachers\StoreTeacherRequest;
use App\Http\Requests\V1\Teachers\UpdateTeacherRequest;
use App\Http\Resources\Collections\TeacherCollection;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Services\Http\Controllers\Api\V1\Teacher\TeacherServiceInterface;

final class TeacherService implements TeacherServiceInterface
{
    public function getAll() : TeacherCollection
    {
        return new TeacherCollection(Teacher::with('user')->get());
    }

    public function getById(string $id) : TeacherResource
    {
        $teacher = Teacher::with('user')->find($id);
        if($teacher === null) {
            throw new ResourceNotFoundException('Teacher ' . $id . ' not found.');
        }
        return new TeacherResource($teacher);
    }

    public function save (StoreTeacherRequest $request) : TeacherResource
    {
    }

    public function update (UpdateTeacherRequest $request) : TeacherResource
    {
    }

    public function delete (string $id) : TeacherResource
    {
        $student = Teacher::with('user')->find($id);
        if($student === null) {
            throw new ResourceNotFoundException('Teacher ' . $id . ' does not exist.');
        }
        $student->delete();
        return new TeacherResource($student);
    }
}
