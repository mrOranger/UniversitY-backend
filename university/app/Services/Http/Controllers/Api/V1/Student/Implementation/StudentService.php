<?php

namespace App\Services\Http\Controllers\Api\V1\Student\Implementation;

use App\Exceptions\ResourceConflictException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\V1\Students\StudentRequest;
use App\Http\Resources\collections\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Degree;
use App\Models\Student;
use App\Services\Http\Controllers\Api\V1\Student\StudentServiceInterface;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class StudentService implements StudentServiceInterface
{
    public final function getAll(): StudentCollection
    {
        return new StudentCollection(Student::with('degree')->get());
    }

    public final function getById(string $id): StudentResource
    {
        $student = Student::find($id);
        if ($student === null) {
            throw new ResourceNotFoundException('Student ' . $id . ' does not exist.');
        }
        return new StudentResource($student);
    }

    public final function save(StudentRequest $studentRequest): StudentResource
    {
        $degree = Degree::where('name', '=', $studentRequest->degree['name'])
            ->where('code', '=', $studentRequest->degree['code'])
            ->where('course_type', '=', $studentRequest->degree['course_type'])
            ->first();
        if ($degree === null) {
            $degree = Degree::create($studentRequest->degree);
        }
        $student = new Student([
            'bachelor_final_mark' => $studentRequest->bachelor_final_mark,
            'master_final_mark' => $studentRequest->master_final_mark,
            'phd_final_mark' => $studentRequest->phd_final_mark,
            'outside_prescribed_time' => $studentRequest->outside_prescribed_time,
            'degree_id' => $degree->id
        ]);
        $student->degree()->associate($degree);
        $student->save();
        return new StudentResource($student);
    }

    public final function update(StudentRequest $studentRequest, string $id): StudentResource
    {
        $student = Student::find($id);
        if ($student === null) {
            throw new ResourceNotFoundException('Student ' . $id . ' does not exist.');
        }
        $degree = Degree::where('name', '=', $studentRequest->degree['name'])
            ->where('code', '=', $studentRequest->degree['code'])
            ->where('course_type', '=', $studentRequest->degree['course_type'])
            ->first();
        if ($degree === null) {
            $degree = Degree::create($studentRequest->degree);
        }
        $student->degree()->associate($degree);
        $student->update([
            'bachelor_final_mark' => $studentRequest->bachelor_final_mark,
            'master_final_mark' => $studentRequest->master_final_mark,
            'phd_final_mark' => $studentRequest->phd_final_mark,
            'outside_prescribed_time' => $studentRequest->outside_prescribed_time,
            'degree_id' => $degree->id
        ]);
        return new StudentResource($student);
    }

    public final function delete (string $id) : StudentResource
    {
        $student = Student::find($id);
        if($student === null) {
            throw new ResourceNotFoundException('Student ' . $id . ' does not exist.');
        }
        $student->delete();
        return new StudentResource($student);
    }
}
