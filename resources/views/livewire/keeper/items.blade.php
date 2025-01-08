<?php

use App\Models\Item;
use Filament\Tables\Table;

use App\Enums\ItemType;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

// use function Livewire\Volt\{state, layout, usesPagination, mount, with, action, computed, on};

new  class extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    public ?Item $model=null;
    public $creating = false;
    public $sortBy = ['column' => 'id', 'direction' => 'asc'];


    #[Computed]
    public function hasModel()
    {
        return $this->model != null;   
    }

    public function resetQueries()
    {
        $this->search = null;
        $this->resetPage();
    }

    public function setItem(Item $item){
        $this->model = $item;
        $this->dispatch('item-changed', item: $item);
        $this->dispatch('open-modal', 'model-edit');

    }

    protected function getQuery(): Builder
    {
        return Item::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy(...array_values($this->sortBy));
    }

    #[On('item-created')]
    public function handleItemCreated($item)
    {
        $this->resetQueries();
        $this->closeCreateModal($item);
    }

    #[On('item-updated')]
    public function handleItemUpdated($item)
    {
        $this->resetQueries();
        if ($item !== null) {
            $this->search = $item['name'];
        }
    }

    #[On('item-create-cancelled')]
    public function handleItemCreateCancelled()
    {
        $this->closeCreateModal();
    }

    public function openCreateModal()
    {
        $this->creating = true;
        $this->dispatch('open-modal', 'model-create');
    }

    public function closeCreateModal($model = null)
    {
        $this->dispatch('close-modal', 'model-create');
        if ($model !== null) {
            $this->search = $model['name'];
        }
    }

    public function with(): array
    {
        return [
            'items' => $this->getQuery()->paginate(),
        ];
    }
    
}; 






?>

<div class="relative p-6 overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex items-center gap-4">
        <x-mary-input clearable label="Search" placeholder="Search a item" icon="o-magnifying-glass" hint="Start typing the item name to search" wire:model.live="search" />
        <button wire:click="openCreateModal" type="button" class="btn btn-outline btn-primary">Create Item</button>
        <button wire:click="resetQueries " type="button" class="btn btn-outline btn-primary">Reset Queries </button>
        <div class="flex gap-4">

        </div>
    </div>
    @php
    $headers = [
    ['key' => 'id', 'label' => '#'],
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'quantity', 'label' => 'quantity'],
    ['key' => 'default_limit', 'label' => 'Daily Limit'],
    ['key' => 'created_at', 'label' => 'created']
    ];
    @endphp
    <x-mary-table :headers="$headers" :rows="$items" striped @row-click="$wire.setItem($event.detail.id)" :sort-by="$sortBy" with-pagination>

        @scope('actions', $item)
        <div class="flex gap-1">

            <x-mary-button icon="o-pencil" wire:click="setItem({{ $item->id }})" spinner class="btn-sm" />
            {{-- <x-mary-button icon="o-trash" wire:click="delete({{ $item->id }})" spinner class="btn-sm" /> --}}

        </div>
        @endscope

    </x-mary-table>

    <x-modal name name="model-create" :show="$this->creating">
        <livewire:keeper.items.create-item />
    </x-modal>

    <!-- Edit interest modal -->
    <x-modal name="model-edit" :show="$this->hasModel" focusable>

        @if ($this->model!=null)
        <livewire:keeper.items.edit-item :item="$this->model" />
        @endif
    </x-modal>




</div>