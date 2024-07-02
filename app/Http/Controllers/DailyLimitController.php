<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyLimitRequest;
use App\Http\Requests\UpdateDailyLimitRequest;
use App\Models\DailyLimit;

class DailyLimitController extends Controller
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
    public function store(StoreDailyLimitRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyLimit $dailyLimit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyLimit $dailyLimit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDailyLimitRequest $request, DailyLimit $dailyLimit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyLimit $dailyLimit)
    {
        //
    }
}
