<?php

namespace App\Services\Http\Controllers\Api\V1\Student;
use App\Http\Resources\collections\StudentCollection;

interface StudentServiceInterface
{
    public function getAll() : StudentCollection;
}
