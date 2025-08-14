<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\TenancyPeriod;
use App\Models\Tenant;
use Illuminate\Routing\Controller;

class TenantController extends Controller
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
        return response()->json(Tenant::all(), 200);
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
    public function store(StoreTenantRequest $request)
    {
        $request->validate([
            'tenancy_period_id' => 'required|exists:tenancy_periods,id',
            'name' => 'required|string|max:255',
            'move_in_date' => 'required|date',
        ]);

        $tenancyPeriod = TenancyPeriod::findOrFail($request->tenancy_period_id);

        $tenant = Tenant::create([
            'tenancy_period_id' => $tenancyPeriod->id,
            'name' => $request->name,
            'move_in_date' => $request->move_in_date,
            'height' => $tenancyPeriod->height + 1,
            'type' => 'Tenant'
        ]);

        return response()->json(['status' => 'success', 'data' => $tenant], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        return response()->json($tenant, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        $request->validate([
            'tenancy_period_id' => 'sometimes|exists:tenancy_periods,id',
            'name' => 'sometimes|string|max:255',
            'move_in_date' => 'sometimes|date',
        ]);

        if ($request->has('tenancy_period_id')) {
            $tenancyPeriod = TenancyPeriod::findOrFail($request->tenancy_period_id);
            $tenant->tenancy_period_id = $tenancyPeriod->id;
            $tenant->height = $tenancyPeriod->height + 1;
        }

        if ($request->has('name')) $tenant->name = $request->name;
        if ($request->has('move_in_date')) $tenant->move_in_date = $request->move_in_date;

        $tenant->save();

        return response()->json(['status' => 'success', 'data' => $tenant], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return response()->json(['status' => 'success', 'message' => 'Tenant deleted'], 200);
    }
}
