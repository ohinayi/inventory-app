<?php

use App\Models\ConsumptionRequest;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;

new class extends Component {
    use WithPagination;

    #[Url]
    public $search = '';
    public ?ConsumptionRequest $model = null;
    public $sortBy = ['column' => 'id', 'direction' => 'desc'];
    public $filterStatus = '';

    #[Computed]
    public function hasModel()
    {
        return $this->model != null;
    }

    public function resetQueries()
    {
        $this->search = null;
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function setRequest(ConsumptionRequest $request)
    {
        $this->model = $request;
        $this->dispatch('open-modal', 'request-detail');
    }

    protected function getQuery(): Builder
    {
        return ConsumptionRequest::query()
            ->with(['user', 'item', 'approvedBy'])
            ->when($this->search, fn($query) => 
                $query->whereHas('item', fn($q) => 
                    $q->where('name', 'like', '%' . $this->search . '%')
                )
            )
            ->when($this->filterStatus, fn($query) => 
                $query->where('status', $this->filterStatus)
            )
            ->orderBy(...array_values($this->sortBy));
    }

    public function approve(ConsumptionRequest $request)
    {
        $request->update([
            'status' => 'approved',
            'approved_by_id' => auth()->id(),
            'approved_at' => now()
        ]);
        $this->dispatch('request-updated');
    }

    public function reject(ConsumptionRequest $request, $reason)
    {
        $request->update([
            'status' => 'rejected',
            'reason' => $reason,
            'approved_by_id' => auth()->id(),
            'approved_at' => now()
        ]);
        $this->dispatch('request-updated');
    }

    public function with(): array
    {
        return [
            'requests' => $this->getQuery()->paginate()
        ];
    }
};
?>

<div class="relative p-6 overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex items-center gap-4">
        <x-mary-input 
            clearable 
            label="Search" 
            placeholder="Search by item name" 
            icon="o-magnifying-glass" 
            wire:model.live="search" 
        />
        <select wire:model.live="filterStatus" class="select select-bordered">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
        <button wire:click="resetQueries" class="btn btn-outline btn-primary">Reset Filters</button>
    </div>

    @php
    $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'created_at', 'label' => 'Requested At'],
        ['key' => 'user.name', 'label' => 'Requested By'],
        ['key' => 'item.name', 'label' => 'Item'],
        ['key' => 'quantity', 'label' => 'Quantity'],
        ['key' => 'status', 'label' => 'Status'],
    ];
    @endphp

    <x-mary-table 
        :headers="$headers" 
        :rows="$requests" 
        striped 
        :sort-by="$sortBy" 
        with-pagination
    >
        @scope('cell_status', $request)
            <span class="badge badge-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'approved' ? 'success' : 'error') }}">
                {{ ucfirst($request->status) }}
            </span>
        @endscope

        @scope('actions', $request)
            <div class="flex gap-1">
                @if($request->status === 'pending')
                    <x-mary-button 
                        icon="o-check" 
                        wire:click="approve({{ $request->id }})" 
                        spinner 
                        class="btn-sm btn-success" 
                    />
                    <x-mary-button 
                        icon="o-x-mark" 
                        x-on:click="$dispatch('open-modal', 'reject-modal-{{ $request->id }}')" 
                        class="btn-sm btn-error" 
                    />
                @endif
                <x-mary-button 
                    icon="o-eye" 
                    wire:click="setRequest({{ $request->id }})" 
                    spinner 
                    class="btn-sm" 
                />
            </div>

            <x-modal name="reject-modal-{{ $request->id }}" focusable>
                <div class="p-6">
                    <h2 class="text-lg font-medium">Reject Request</h2>
                    <div class="mt-4">
                        <textarea 
                            wire:model="rejectReason" 
                            class="w-full textarea textarea-bordered" 
                            placeholder="Enter reason for rejection"
                        ></textarea>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <x-mary-button 
                            wire:click="reject({{ $request->id }}, $rejectReason)" 
                            class="btn-error"
                        >
                            Confirm Reject
                        </x-mary-button>
                    </div>
                </div>
            </x-modal>
        @endscope
    </x-mary-table>

    <x-modal name="request-detail" :show="$this->hasModel" focusable>
        @if($this->model)
        <div class="p-6">
            <h2 class="text-lg font-medium">Request Details</h2>
            <div class="mt-4 space-y-4">
                <div>
                    <span class="font-bold">Requested By:</span> 
                    {{ $this->model->user->name }}
                </div>
                <div>
                    <span class="font-bold">Item:</span> 
                    {{ $this->model->item->name }}
                </div>
                <div>
                    <span class="font-bold">Quantity:</span> 
                    {{ $this->model->quantity }}
                </div>
                <div>
                    <span class="font-bold">Status:</span> 
                    {{ ucfirst($this->model->status) }}
                </div>
                @if($this->model->approved_by_id)
                <div>
                    <span class="font-bold">Handled By:</span> 
                    {{ $this->model->approvedBy->name }}
                </div>
                @endif
                @if($this->model->reason)
                <div>
                    <span class="font-bold">Reason:</span> 
                    {{ $this->model->reason }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </x-modal>
</div>