<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumptionRequestRequest;
use App\Models\ConsumptionRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConsumptionRequestController extends Controller
{

    public function index () {

        $userId = auth()->id();
        $items = Item::all();
        $consumptionRequests = ConsumptionRequest::where('user_id', $userId)->with('item')->get();
        return Inertia::render('Consumption/Index', ['items'=>$items, 'consumptionRequests'=> $consumptionRequests]);
    }
    public function store(ConsumptionRequestRequest $request)
    {
        auth()->user()->consumptionRequests()->create($request->all());
        return redirect()->back();
    }
    public function update() {}

    public function delete() {}
}
