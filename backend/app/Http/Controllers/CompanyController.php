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

        // Eager load the subsectors relationship
        $query->with(['subsectors', 'surveys', 'kab', 'kec', 'des', 'bs']);

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
            'kab_id' => $request->kab,
            'kec_id' => $request->kec,
            'des_id' => $request->des,
            'bs_id' => $request->bs,
            'address' => $request->address,
            'xcoordinate' => $request->xcoordinate,
            'ycoordinate' => $request->ycoordinate,
        ]);

        $company->surveys()->sync(json_decode($request->surveys, true));
        $company->subsectors()->sync(json_decode($request->subsectors, true));
        $company->load('subsectors', 'surveys');

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
