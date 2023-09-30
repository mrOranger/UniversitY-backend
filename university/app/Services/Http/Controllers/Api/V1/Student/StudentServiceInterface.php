<?php

namespace App\Services\Http\Controllers\Api\V1\Student;
use App\Http\Requests\V1\Students\StudentRequest;
use App\Http\Resources\collections\StudentCollection;
use App\Http\Resources\StudentResource;

interface StudentServiceInterface
{
    public function getAll() : StudentCollection;
    public function getById (string $id) : StudentResource;
    public function save (StudentRequest $studentRequest) : StudentResource;
    public function update (StudentRequest $studentRequest, string $id) : StudentResource;
}
