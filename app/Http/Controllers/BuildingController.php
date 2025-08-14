<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Models\Building;
use App\Models\Corporation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BuildingController extends Controller
{
    public function __construct()
    {
        // Auth middleware to all controller methods
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate(['corporation_id' => 'required|exists:corporations,id']);
        return Building::where('corporation_id', $request->corporation_id)->get();
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
    public function store(StoreBuildingRequest $request)
    {
        $request->validate([
            'name' => 'required|string',
            'corporation_id' => 'required|exists:corporations,id',
            'zip_code' => 'required|string',
        ]);

        $corporation = Corporation::find($request->corporation_id);

        return Building::create([
            'name' => $request->name,
            'corporation_id' => $corporation->id,
            'height' => $corporation->height + 1,
            'zip_code' => $request->zip_code,
            'type' => 'Building',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building)
    {
        return $building;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Building $building)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBuildingRequest $request, Building $building)
    {
        $request->validate([
            'name' => 'string',
            'corporation_id' => 'exists:corporations,id',
            'zip_code' => 'string',
        ]);

        if ($request->corporation_id) {
            $building->corporation_id = $request->corporation_id;
            $building->height = Corporation::find($request->corporation_id)->height + 1;
        }

        $building->update($request->only('name', 'zip_code'));
        return $building;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building)
    {
        $building->delete();

        return response()->json([
            'success' => true,
            'message' => 'Building deleted successfully.'
        ]);
    }
}
