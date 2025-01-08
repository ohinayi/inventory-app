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
                'quantity_received' => $item->quantity_received ?? 0,
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

<div>
    <h3>Receive Items</h3>
    @foreach($receivedItems as $index => $item)
        <div>
            {{ $procurement->procurementItems[$index]->item->name }}
            <input type="number" wire:model="receivedItems.{{ $index }}.quantity_received" min="0">
            <textarea wire:model="receivedItems.{{ $index }}.receive_notes" placeholder="Receive notes"></textarea>
        </div>
    @endforeach
    <button wire:click="updateReceived">Update Received Items</button>
</div>