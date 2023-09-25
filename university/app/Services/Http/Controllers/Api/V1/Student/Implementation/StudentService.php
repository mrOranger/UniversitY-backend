<?php

namespace App\Services\Http\Controllers\Api\V1\Student\Implementation;
use App\Exceptions\ResourceConflictException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\V1\Students\StudentRequest;
use App\Http\Resources\collections\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Services\Http\Controllers\Api\V1\Student\StudentServiceInterface;

final class StudentService implements StudentServiceInterface
{
    public final function getAll() : StudentCollection
    {
        return new StudentCollection(Student::with('degree')->get());
    }

    public final function getById(string $id) : StudentResource
    {
        $student = Student::find($id);
        if($student === null) {
            throw new ResourceNotFoundException('Student ' . $id . ' does not exist.');
        }
        return new StudentResource($student);
    }

    public final function save (StudentRequest $studentRequest) : StudentResource
    {
        $studentFields = $studentRequest->validated();
        if(Student::find($studentFields['id']) !== null) {
            throw new ResourceConflictException('Student ' . $studentFields['id'] . ' is already registered.');
        }
        $student = new Student($studentFields);
        $student->save();
        return new StudentResource($student);
    }
}
