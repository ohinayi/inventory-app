<?php

use Livewire\Volt\Component;
use App\Models\Procurement;
use App\Models\ProcurementItem;

new class extends Component {
    public Procurement $procurement;
    public $receivedItems = [];

    protected $rules = [
        'receivedItems.*.quantity_received' => 'required|integer|min:0',
        'receivedItems.*.receive_notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->receivedItems = $this->procurement->procurementItems->map(function ($item) {
            return [
                'id' => $item->id,
                'quantity_received' => $item->quantity_received ?? $item->quantity_requested,
                'receive_notes' => $item->receive_notes,
            ];
        })->toArray();
    }

    public function updateReceived()
    {
        $this->validate();

        foreach ($this->receivedItems as $receivedItem) {
            $procurementItem = ProcurementItem::find($receivedItem['id']);
            $procurementItem->update([
                'quantity_received' => $receivedItem['quantity_received'],
                'receive_notes' => $receivedItem['receive_notes'],
            ]);

            // Update item quantity in inventory
            $item = $procurementItem->item;
            $item->increment('quantity', $receivedItem['quantity_received']);
        }

        $this->updateProcurementStatus();

        $this->dispatch('itemsReceived');
    }

    private function updateProcurementStatus()
    {
        $allReceived = $this->procurement->procurementItems->every(function ($item) {
            return $item->quantity_received == $item->quantity_requested;
        });

        $partiallyReceived = $this->procurement->procurementItems->some(function ($item) {
            return $item->quantity_received > 0 && $item->quantity_received < $item->quantity_requested;
        });

        if ($allReceived) {
            $this->procurement->update(['status' => 'fully_received']);
        } elseif ($partiallyReceived) {
            $this->procurement->update(['status' => 'partially_received']);
        }
    }
}; ?>


<div class="p-8 bg-white rounded-lg shadow-lg max-w-4xl mx-auto">
    <h3 class="text-2xl font-bold mb-8 text-gray-800 border-b pb-4">Received Items</h3>
    
    @foreach($receivedItems as $index => $item)
    <div class="mb-6 p-6 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-150">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="text-lg font-medium text-gray-700">
                {{ $procurement->procurementItems[$index]->item->name }}
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex flex-col">
                    <label class="text-sm text-gray-600 mb-1">Quantity Received</label>
                    <input 
                        type="number" 
                        wire:model="receivedItems.{{ $index }}.quantity_received" 
                        min="0" 
                        value="{{ $item['quantity_received'] }}"
                        class="px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                <div class="flex flex-col flex-grow">
                    <label class="text-sm text-gray-600 mb-1">Notes</label>
                    <textarea 
                        wire:model="receivedItems.{{ $index }}.receive_notes" 
                        placeholder="Enter receive notes here..."
                        class="px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 h-20 resize-none"
                    ></textarea>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="mt-8 flex justify-end">
        <button 
            wire:click="updateReceived"
            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150"
        >
            Confirm Receive
        </button>
    </div>
</div>