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

<div>
    <form wire:submit.prevent="submit">
        <div>
            <label for="reason">Reason for Procurement</label>
            <textarea id="reason" wire:model="reason"></textarea>
        </div>

        <div>
            <h3>Select Items</h3>
            @foreach($items as $item)
                <button type="button" wire:click="addItem({{ $item->id }})">
                    Add {{ $item->name }}
                </button>
            @endforeach
        </div>

        <div>
            <h3>Selected Items</h3>
            @foreach($selectedItems as $index => $item)
                <div>
                    {{ $items->find($item['id'])->name }}
                    <input type="number" wire:model="selectedItems.{{ $index }}.quantity" min="1">
                    <button type="button" wire:click="removeItem({{ $index }})">Remove</button>
                </div>
            @endforeach
        </div>

        <button type="submit">Create Procurement</button>
    </form>
</div>