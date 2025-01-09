<?php

namespace App\Http\Controllers;

use App\Models\ConsumptionRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
            $userId = auth()->id();
            $items = Item::all();
            $consumptionRequests = ConsumptionRequest::where('user_id', $userId)->with('item')->get();
            return Inertia::render('Dashboard', ['items'=>$items, 'consumptionRequests'=> $consumptionRequests]);

    }
    // public function index (){}
}
