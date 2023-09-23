<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Http\Controllers\Api\V1\Degree\DegreeServiceInterface;
use Illuminate\Http\Request;

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
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
