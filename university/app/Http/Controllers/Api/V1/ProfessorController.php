<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Professors\StoreProfessorRequest;
use App\Http\Requests\V1\Professors\UpdateProfessorRequest;
use App\Models\Professor;
use App\Services\Http\Controllers\Api\V1\Professor\ProfessorServiceInterface;

class ProfessorController extends Controller
{
    private ProfessorServiceInterface $professorServiceInterface;

    public function __construct(ProfessorServiceInterface $professorServiceInterface)
    {
        $this->professorServiceInterface = $professorServiceInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->professorServiceInterface->getAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProfessorRequest $request save new professor request.
     */
    public function store(StoreProfessorRequest $request)
    {
        return $this->professorServiceInterface->save($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Professor $professor professor to show.
     */
    public function show(Professor $professor)
    {
        return $this->professorServiceInterface->getById($professor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProfessorRequest $request update existing professor request.
     * @param Professor $professor professor to update.
     */
    public function update(UpdateProfessorRequest $request, Professor $professor)
    {
        return $this->professorServiceInterface->update($request, $professor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Professor $professor professor to delete.
     */
    public function destroy(Professor $professor)
    {
        return $this->professorServiceInterface->delete($professor);
    }
}
