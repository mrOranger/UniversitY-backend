<?php

namespace App\Services\Http\Controllers\Api\V1\Student\Implementation;
use App\Http\Resources\collections\StudentCollection;
use App\Models\Student;
use App\Services\Http\Controllers\Api\V1\Student\StudentServiceInterface;

final class StudentService implements StudentServiceInterface
{
    public final function getAll() : StudentCollection
    {
        return new StudentCollection(Student::all());
    }
}
