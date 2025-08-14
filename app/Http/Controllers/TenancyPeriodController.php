<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenancyPeriodRequest;
use App\Http\Requests\UpdateTenancyPeriodRequest;
use App\Models\Property;
use App\Models\TenancyPeriod;

class TenancyPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(TenancyPeriod::all(), 200);
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
    public function store(StoreTenancyPeriodRequest $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'active' => 'sometimes|boolean',
        ]);

        $property = Property::findOrFail($request->property_id);

        $tenancyPeriod = TenancyPeriod::create([
            'property_id' => $property->id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'active' => $request->active ?? false,
            'height' => $property->height + 1,
            'type' => 'Tenancy Period'
        ]);

        return response()->json(['status' => 'success', 'data' => $tenancyPeriod], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TenancyPeriod $tenancyPeriod)
    {
        return response()->json($tenancyPeriod, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TenancyPeriod $tenancyPeriod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenancyPeriodRequest $request, TenancyPeriod $tenancyPeriod)
    {
        if ($request->has('property_id')) {
            $property = Property::findOrFail($request->property_id);
            $tenancyPeriod->property_id = $property->id;
            $tenancyPeriod->height = $property->height + 1;
        }

        if ($request->has('name')) $tenancyPeriod->name = $request->name;
        if ($request->has('start_date')) $tenancyPeriod->start_date = $request->start_date;
        if ($request->has('end_date')) $tenancyPeriod->end_date = $request->end_date;
        if ($request->has('active')) $tenancyPeriod->active = $request->active;

        $tenancyPeriod->save();

        return response()->json(['status' => 'success', 'data' => $tenancyPeriod], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TenancyPeriod $tenancyPeriod)
    {
        $tenancyPeriod->delete();
        return response()->json(['status' => 'success', 'message' => 'Tenancy Period deleted'], 200);
    }
}
