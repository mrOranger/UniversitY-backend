<?php

namespace App\Services\Http\Controllers\Api\V1\Degree;
use App\Http\Resources\DegreeResource;

interface DegreeServiceInterface
{
    public function getAll() : DegreeResource;
}
