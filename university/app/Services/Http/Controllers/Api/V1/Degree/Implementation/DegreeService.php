<?php

namespace App\Services\Http\Controllers\Api\V1\Degree\Implementation;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\V1\Degrees\DegreeRequest;
use App\Http\Requests\V1\Degrees\UpdateDegreeRequest;
use App\Http\Resources\Collections\DegreeCollection;
use App\Http\Resources\DegreeResource;
use App\Models\Degree;
use App\Services\Http\Controllers\Api\V1\Degree\DegreeServiceInterface;

final class DegreeService implements DegreeServiceInterface
{
    final public function getAll(): DegreeCollection
    {
        return new DegreeCollection(Degree::all());
    }

    final public function getById(string $id): DegreeResource
    {
        $degree = Degree::find($id);
        if ($degree === null) {
            throw new ResourceNotFoundException('Degree '.$id.' does not exist.');
        }

        return new DegreeResource($degree);
    }

    final public function save(DegreeRequest $degreeRequest): DegreeResource
    {
        $degree = new Degree($degreeRequest->toArray());
        $degree->save();

        return new DegreeResource($degree);
    }

    final public function update(UpdateDegreeRequest $degreeRequest, string $id): DegreeResource
    {
        $degree = Degree::find($id);
        if ($degree === null) {
            throw new ResourceNotFoundException('Degree '.$id.' does not exist.');
        }
        $degree->update($degreeRequest->toArray());

        return new DegreeResource($degree);
    }

    final public function deleteById(string $id): DegreeResource
    {
        $degree = Degree::find($id);
        if ($degree === null) {
            throw new ResourceNotFoundException('Degree '.$id.' does not exist.');
        }
        $degree->delete();

        return new DegreeResource($degree);
    }
}
