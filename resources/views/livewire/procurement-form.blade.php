<?php

use Livewire\Volt\Component;
use App\Models\Item;
use App\Models\Procurement;
use App\Models\ProcurementItem;

new class extends Component {
    public $reason = '';
    public $items = [];
    public $selectedItems = [];

    protected $rules = [
        'reason' => 'required|string',
        'selectedItems.*.id' => 'required|exists:items,id',
        'selectedItems.*.quantity' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->items = Item::all();
    }

    public function addItem($itemId)
    {
        $this->selectedItems[] = [
            'id' => $itemId,
            'quantity' => 1,
        ];
    }

    public function removeItem($index)
    {
        unset($this->selectedItems[$index]);
        $this->selectedItems = array_values($this->selectedItems);
    }

    public function submit()
    {
        $this->validate();

        $procurement = Procurement::create([
            'user_id' => auth()->id(),
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        foreach ($this->selectedItems as $item) {
            ProcurementItem::create([
                'procurement_id' => $procurement->id,
                'item_id' => $item['id'],
                'quantity_requested' => $item['quantity'],
            ]);
        }

        $this->reset(['reason', 'selectedItems']);
        $this->dispatch('procurementCreated');
        $this->dispatch('viewChanged', 'list');
    }
}; ?>

<div class="max-w-4xl mx-auto">
    <form wire:submit.prevent="submit" class="space-y-8">
        <!-- Reason Section -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for Procurement
                </label>
                <textarea 
                    id="reason" 
                    wire:model="reason"
                    rows="4"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Please provide a detailed reason for this procurement request..."
                ></textarea>
            </div>
        </div>

        <!-- Item Selection Section -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Select Items</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                @foreach($items as $item)
                    <button 
                        type="button" 
                        wire:click="addItem({{ $item->id }})"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <span class="mr-2">+</span>
                        {{ $item->name }}
                    </button>
                @endforeach
            </div>

            <!-- Selected Items List -->
            @if(count($selectedItems) > 0)
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Selected Items</h3>
                    <div class="space-y-4">
                        @foreach($selectedItems as $index => $item)
                            <div class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg">
                                <div class="flex-1">
                                    <span class="font-medium text-gray-900">
                                        {{ $items->find($item['id'])->name }}
                                    </span>
                                </div>
                                <div class="w-32">
                                    <input 
                                        type="number" 
                                        wire:model="selectedItems.{{ $index }}.quantity"
                                        min="1"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                </div>
                                <button 
                                    type="button" 
                                    wire:click="removeItem({{ $index }})"
                                    class="inline-flex items-center p-2 border border-transparent rounded-full text-red-600 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button 
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Create Procurement
            </button>
        </div>
    </form>
</div>