<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Degrees\DegreeRequest;
use App\Http\Requests\V1\Degrees\UpdateDegreeRequest;
use App\Http\Resources\Collections\DegreeCollection;
use App\Services\Http\Controllers\Api\V1\Degree\DegreeServiceInterface;

class DegreeController extends Controller
{
    private DegreeServiceInterface $degreeServiceInterface;

    public function __construct(DegreeServiceInterface $degreeServiceInterface)
    {
        $this->degreeServiceInterface = $degreeServiceInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): DegreeCollection
    {
        return $this->degreeServiceInterface->getAll();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->degreeServiceInterface->getById($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DegreeRequest $request)
    {
        return $this->degreeServiceInterface->save($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDegreeRequest $request, string $id)
    {
        return $this->degreeServiceInterface->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->degreeServiceInterface->deleteById($id);
    }
}
