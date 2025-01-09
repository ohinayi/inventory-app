<?php

namespace App\Http\Controllers;

use App\Models\ConsumptionRequest;
use App\Models\Item;
use App\Models\Voucher;
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
            $vouchers = Voucher::where('user_id', $userId)->latest()->limit(4)->get();
            $consumptionRequests = ConsumptionRequest::where('user_id', $userId)->with('item')->latest()->limit(5)->get();
            return Inertia::render('Dashboard', ['items'=>$items, 'consumptionRequests'=> $consumptionRequests, 'vouchers'=>$vouchers]);

    }
    // public function index (){}
}
