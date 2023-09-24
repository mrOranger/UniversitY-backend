<?php

namespace App\Services\Http\Controllers\Api\V1\Degree;
use App\Http\Resources\Collections\DegreeCollection;

interface DegreeServiceInterface
{
    public function getAll() : DegreeCollection;
}
