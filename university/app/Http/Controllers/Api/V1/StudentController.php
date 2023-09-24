<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Http\Controllers\Api\V1\Student\StudentServiceInterface;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private StudentServiceInterface $studentServiceInterface;

    public function __construct(StudentServiceInterface $studentServiceInterface)
    {
        $this->studentServiceInterface = $studentServiceInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->studentServiceInterface->getAll();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->studentServiceInterface->getById($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
