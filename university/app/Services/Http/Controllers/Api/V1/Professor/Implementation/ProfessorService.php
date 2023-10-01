<?php

namespace App\Services\Http\Controllers\Api\V1\Professor\Implementation;
use App\Http\Requests\V1\Professors\StoreProfessorRequest;
use App\Http\Requests\V1\Professors\UpdateProfessorRequest;
use App\Http\Resources\Collections\ProfessorCollection;
use App\Http\Resources\ProfessorResource;
use App\Models\Professor;
use App\Services\Http\Controllers\Api\V1\Professor\ProfessorServiceInterface;

final class ProfessorService implements ProfessorServiceInterface
{
    public function getAll() : ProfessorCollection
    {
        return new ProfessorCollection(Professor::all());
    }

    public function getById (Professor $professor) : ProfessorResource
    {
        return new ProfessorResource(Professor::all()->first());
    }

    public function save (StoreProfessorRequest $storeProfessorRequest) : ProfessorResource
    {
        return new ProfessorResource(Professor::all()->first());
    }

    public function update (UpdateProfessorRequest $updateProfessorRequest, Professor $professor) : ProfessorResource
    {
        return new ProfessorResource(Professor::all()->first());
    }

    public function delete (Professor $professor) : ProfessorResource
    {
        return new ProfessorResource(Professor::all()->first());
    }
}
