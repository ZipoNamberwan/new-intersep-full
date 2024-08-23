<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Company::query();

        // Apply filtering based on request inputs
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('area')) {
            $query->where('area', $request->input('area'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        // You can add more filters as needed...

        // Eager load the subsectors relationship
        $query->with('subsectors');
        $query->with('surveys');

        // Apply pagination
        $companies = $query->paginate(10); // Adjust the number for pagination size

        // Return a resource collection with pagination
        return CompanyResource::collection($companies);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
