<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize the query
        $query = Company::query();

        // Apply filtering by area if provided
        if ($request->has('area')) {
            $query->where('area', $request->input('area'));
        }

        // Apply filtering by type if provided
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        // Paginate the results, default to 15 per page if not specified
        $companies = $query->paginate($request->input('per_page', 15));

        // Return the paginated result
        return response()->json($companies);
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
