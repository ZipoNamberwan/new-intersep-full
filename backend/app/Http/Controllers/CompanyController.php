<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
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
        if ($request->has('keyword')) {
            $query->where('name', 'like', '%' . $request->input('keyword') . '%')->orWhere('address', 'like', '%' . $request->input('keyword') . '%');
        }

        if ($request->has('subsectors')) {
            $subsectorIds = $request->input('subsectors');
            $query->whereHas('subsectors', function ($query) use ($subsectorIds) {
                $query->whereIn('subsector_id', $subsectorIds);
            });
        }

        if ($request->has('surveys')) {
            $surveyIds = $request->input('surveys');
            $query->whereHas('surveys', function ($query) use ($surveyIds) {
                $query->whereIn('survey_id', $surveyIds);
            });
        }

        // You can add more filters as needed...

        // Eager load the subsectors relationship
        $query->with('subsectors');
        $query->with('surveys');

        // Apply pagination
        $companies = $query->paginate($request->pageSize ?? 10); // Adjust the number for pagination size

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
    public function store(CompanyRequest $request)
    {
        $request->validated();

        $company = Company::create([
            'id_sbr' => $request->id_sbr,
            'name' => $request->name,
            'kab' => $request->kab,
            'kec' => $request->kec,
            'des' => $request->des,
            'bs' => $request->bs,
            'address' => $request->address,
            'xcoordinate' => $request->xcoordinate,
            'ycoordinate' => $request->ycoordinate,
        ]);

        return new CompanyResource($company);
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
