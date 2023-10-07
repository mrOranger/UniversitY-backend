<?php

namespace App\Services\Http\Controllers\Api\V1\Teacher;
use App\Http\Requests\V1\Teachers\StoreTeacherRequest;
use App\Http\Requests\V1\Teachers\UpdateTeacherRequest;
use App\Http\Resources\Collections\TeacherCollection;
use App\Http\Resources\TeacherResource;

interface TeacherServiceInterface
{
    public function getAll() : TeacherCollection;
    public function getById(string $id) : TeacherResource;
    public function save (StoreTeacherRequest $request) : TeacherResource;
    public function update (UpdateTeacherRequest $request) : TeacherResource;
    public function delete (string $id) : TeacherResource;
}
