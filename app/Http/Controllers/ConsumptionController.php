<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsumptionRequest;
use App\Http\Requests\UpdateConsumptionRequest;
use App\Models\Consumption;

class ConsumptionController extends Controller
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
    public function store(StoreConsumptionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Consumption $consumption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consumption $consumption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConsumptionRequest $request, Consumption $consumption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumption $consumption)
    {
        //
    }
}
