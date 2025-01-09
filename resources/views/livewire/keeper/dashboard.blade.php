<?php
use App\Livewire\Actions\Logout;
use App\Models\Consumption;
use App\Models\ConsumptionRequest;
use App\Models\DailyLimit;
use App\Models\Item;
use App\Models\Procurement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
    public $pendingConsumptionRequests = [];
    public $pendingProcurements = [];
    public $recentConsumptions = [];
    public $itemsNearLimit = [];
    
    public function mount()
    {
        $this->loadDashboardData();
    }
    
    public function loadDashboardData()
    {
        // Get pending consumption requests
        $this->pendingConsumptionRequests = ConsumptionRequest::with(['user', 'item'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
            
        // Get pending procurements
        $this->pendingProcurements = Procurement::with(['user'])
            ->whereIn('status', ['pending', 'partially_received'])
            ->latest()
            ->take(5)
            ->get();
            
        // Get recent consumptions
        $this->recentConsumptions = Consumption::with(['user', 'item'])
            ->latest('consumed_at')
            ->take(5)
            ->get();
            
        // Get items approaching daily limits
        $this->itemsNearLimit = $this->getItemsNearLimit();
    }
    
    private function getItemsNearLimit()
    {
        $items = [];
        $dailyLimits = DailyLimit::with('item')->get();
        
        foreach ($dailyLimits as $limit) {
            $todayConsumption = Consumption::where('item_id', $limit->item_id)
                ->whereDate('consumed_at', today())
                ->sum('quantity');
                
            $percentageUsed = ($todayConsumption / $limit->limit) * 100;
            
            if ($percentageUsed >= 80) {
                $items[] = [
                    'item' => $limit->item,
                    'limit' => $limit->limit,
                    'consumed' => $todayConsumption,
                    'percentage' => $percentageUsed
                ];
            }
        }
        
        return $items;
    }
    
    public function approveConsumptionRequest($requestId)
    {
        $request = ConsumptionRequest::find($requestId);
        $request->update([
            'status' => 'approved',
            'approved_by_id' => Auth::id(),
            'approved_at' => now()
        ]);
        
        $this->loadDashboardData();
    }
    
    public function rejectConsumptionRequest($requestId)
    {
        $request = ConsumptionRequest::find($requestId);
        $request->update([
            'status' => 'rejected',
            'approved_by_id' => Auth::id(),
            'approved_at' => now()
        ]);
        
        $this->loadDashboardData();
    }
};
?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Keeper Dashboard</h2>
    
    <!-- Items Near Limit Alert -->
    @if(count($itemsNearLimit) > 0)
    <div class="mb-6 bg-yellow-100 border-l-4 border-yellow-500 p-4">
        <div class="font-bold">Items Approaching Daily Limits</div>
        @foreach($itemsNearLimit as $item)
        <div class="mt-2">
            <span class="font-semibold">{{ $item['item']->name }}</span>: 
            {{ $item['consumed'] }}/{{ $item['limit'] }} ({{ number_format($item['percentage'], 1) }}%)
        </div>
        @endforeach
    </div>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pending Consumption Requests -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Pending Consumption Requests</h3>
            @if(count($pendingConsumptionRequests) > 0)
                @foreach($pendingConsumptionRequests as $request)
                <div class="mb-4 p-4 border rounded">
                    <div>User: {{ $request->user->name }}</div>
                    <div>Item: {{ $request->item->name }}</div>
                    <div>Quantity: {{ $request->quantity }}</div>
                    <div class="mt-2 space-x-2">
                        <button wire:click="approveConsumptionRequest({{ $request->id }})" 
                                class="bg-green-500 text-white px-3 py-1 rounded">
                            Approve
                        </button>
                        <button wire:click="rejectConsumptionRequest({{ $request->id }})" 
                                class="bg-red-500 text-white px-3 py-1 rounded">
                            Reject
                        </button>
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-gray-500">No pending requests</p>
            @endif
        </div>
        
        <!-- Pending Procurements -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Pending Procurements</h3>
            @if(count($pendingProcurements) > 0)
                @foreach($pendingProcurements as $procurement)
                <div class="mb-4 p-4 border rounded">
                    <div>Requested by: {{ $procurement->user->name }}</div>
                    <div>Status: {{ ucfirst($procurement->status) }}</div>
                    <div>Reason: {{ $procurement->reason }}</div>
                </div>
                @endforeach
            @else
                <p class="text-gray-500">No pending procurements</p>
            @endif
        </div>
        
        <!-- Recent Consumptions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Consumptions</h3>
            @if(count($recentConsumptions) > 0)
                @foreach($recentConsumptions as $consumption)
                <div class="mb-4 p-4 border rounded">
                    <div>User: {{ $consumption->user->name }}</div>
                    <div>Item: {{ $consumption->item->name }}</div>
                    <div>Quantity: {{ $consumption->quantity }}</div>
                    <div>Time: {{ $consumption->consumed_at->diffForHumans() }}</div>
                </div>
                @endforeach
            @else
                <p class="text-gray-500">No recent consumptions</p>
            @endif
        </div>
    </div>
</div>