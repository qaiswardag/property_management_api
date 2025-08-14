<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCorporationRequest;
use App\Http\Requests\UpdateCorporationRequest;
use App\Models\Corporation;

class CorporationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Corporation::all();
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
    public function store(StoreCorporationRequest $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        return Corporation::create([
            'name' => $request->name,
            'height' => 0,
            'type' => 'Corporation',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Corporation $corporation)
    {
        return $corporation;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Corporation $corporation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCorporationRequest $request, Corporation $corporation)
    {
        $request->validate([
            'name' => 'string',
        ]);

        $corporation->update($request->only('name'));
        return $corporation;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Corporation $corporation)
    {
        $corporation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Corporation deleted successfully.'
        ]);
    }
}
