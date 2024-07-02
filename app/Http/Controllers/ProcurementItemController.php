<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcurementItemRequest;
use App\Http\Requests\UpdateProcurementItemRequest;
use App\Models\ProcurementItem;

class ProcurementItemController extends Controller
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
    public function store(StoreProcurementItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProcurementItem $procurementItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProcurementItem $procurementItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProcurementItemRequest $request, ProcurementItem $procurementItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProcurementItem $procurementItem)
    {
        //
    }
}
