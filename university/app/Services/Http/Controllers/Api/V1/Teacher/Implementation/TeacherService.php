<?php

namespace App\Services\Http\Controllers\Api\V1\Teacher\Implementation;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\V1\Teachers\StoreTeacherRequest;
use App\Http\Requests\V1\Teachers\UpdateTeacherRequest;
use App\Http\Resources\Collections\TeacherCollection;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
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
            throw new ResourceNotFoundException('Teacher ' . $id . ' does not exist.');
        }
        return new TeacherResource($teacher);
    }

    public function save (StoreTeacherRequest $request) : TeacherResource
    {
        $user = User::where('first_name', '=', $request->user['first_name'])
            ->where('last_name', '=', $request->user['last_name'])
            ->where('birth_date', '=', $request->user['birth_date'])
            ->where('email', '=', $request->user['email'])
            ->first();

        if($user === null) {
            throw new ResourceNotFoundException('Associated user does not exists.');
        }

        $teacher = new Teacher([
            'role' => $request->role,
            'subject' => $request->subject,
            'user_id' => $user->id
        ]);
        $teacher->save();

        return new TeacherResource($teacher);
    }

    public function update (UpdateTeacherRequest $request, string $teacherId) : TeacherResource
    {
        $user = User::where('first_name', '=', $request->user['first_name'])
            ->where('last_name', '=', $request->user['last_name'])
            ->where('birth_date', '=', $request->user['birth_date'])
            ->where('email', '=', $request->user['email'])
            ->first();
        $teacher = Teacher::find($teacherId);

        if($user === null) {
            throw new ResourceNotFoundException('Associated user does not exists.');
        }

        if ($teacher === null) {
            throw new ResourceNotFoundException('Teacher ' . $teacherId . ' does not exists.');
        }

        $teacher->update([
            'role' => $request->role,
            'subject' => $request->subject,
            'user_id' => $user->id
        ]);

        return new TeacherResource($teacher);
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
