<?php

namespace App\Services\Http\Controllers\Api\V1\Degree;

use App\Http\Requests\V1\Degrees\DegreeRequest;
use App\Http\Requests\V1\Degrees\UpdateDegreeRequest;
use App\Http\Resources\Collections\DegreeCollection;
use App\Http\Resources\DegreeResource;

interface DegreeServiceInterface
{
    public function getAll(): DegreeCollection;
    public function getById(string $id): DegreeResource;
    public function save(DegreeRequest $degreeRequest): DegreeResource;
    public function update(UpdateDegreeRequest $degreeRequest, string $id) : DegreeResource;
    public function deleteById(string $id): DegreeResource;
}
