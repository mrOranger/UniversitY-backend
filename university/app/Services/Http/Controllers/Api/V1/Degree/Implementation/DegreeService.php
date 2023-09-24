<?php

namespace App\Services\Http\Controllers\Api\V1\Degree\Implementation;

use App\Http\Resources\Collections\DegreeCollection;
use App\Models\Degree;
use App\Services\Http\Controllers\Api\V1\Degree\DegreeServiceInterface;

final class DegreeService implements DegreeServiceInterface
{
    public final function getAll() : DegreeCollection
    {
        return new DegreeCollection(Degree::all());
    }
}
