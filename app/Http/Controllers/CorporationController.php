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
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Corporation $corporation)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Corporation $corporation)
    {
        //
    }
}
