<?php

namespace App\Services\Http\Controllers\Api\V1\Professor;
use App\Http\Requests\V1\Professors\StoreProfessorRequest;
use App\Http\Requests\V1\Professors\UpdateProfessorRequest;
use App\Http\Resources\Collections\ProfessorCollection;
use App\Http\Resources\ProfessorResource;
use App\Models\Professor;

interface ProfessorServiceInterface
{
    public function getAll() : ProfessorCollection;
    public function getById (Professor $professor) : ProfessorResource;
    public function save (StoreProfessorRequest $storeProfessorRequest) : ProfessorResource;
    public function update (UpdateProfessorRequest $updateProfessorRequest, Professor $professor) : ProfessorResource;
    public function delete (Professor $professor) : ProfessorResource;
}
