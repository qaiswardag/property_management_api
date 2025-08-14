<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Building;
use App\Models\Property;
use Illuminate\Routing\Controller;

class PropertyController extends Controller
{
    public function __construct()
    {
        // Auth middleware to all controller methods
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Property::all(), 200);
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
    public function store(StorePropertyRequest $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'monthly_rent' => 'required|numeric|min:0',
        ]);

        $building = Building::findOrFail($request->building_id);

        $property = Property::create([
            'building_id' => $building->id,
            'name' => $request->name,
            'monthly_rent' => $request->monthly_rent,
            'height' => $building->height + 1,
            'type' => 'Property'
        ]);

        return response()->json(['status' => 'success', 'data' => $property], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        return response()->json($property, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $request->validate([
            'building_id' => 'sometimes|exists:buildings,id',
            'name' => 'sometimes|string|max:255',
            'monthly_rent' => 'sometimes|numeric|min:0',
        ]);

        if ($request->has('building_id')) {
            $building = Building::findOrFail($request->building_id);
            $property->building_id = $building->id;
            $property->height = $building->height + 1;
        }

        if ($request->has('name')) $property->name = $request->name;
        if ($request->has('monthly_rent')) $property->monthly_rent = $request->monthly_rent;

        $property->save();

        return response()->json(['status' => 'success', 'data' => $property], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $property->delete();
        return response()->json(['status' => 'success', 'message' => 'Property deleted'], 200);
    }
}
