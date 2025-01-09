<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumptionRequestRequest;
use App\Models\ConsumptionRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConsumptionRequestController extends Controller
{
    public function store(ConsumptionRequestRequest $request)
    {
        auth()->user()->consumptionRequests()->create($request->all());
        return redirect()->back();
    }
    public function update() {}

    public function delete() {}
}
