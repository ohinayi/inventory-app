<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Procurement;

new class extends Component {
    use WithPagination;

    public $status = '';
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function selectProcurement($id)
    {
        $this->dispatch('procurementSelected', $id);
    }

    public function with(): array
    {
        return [
            'procurements' => Procurement::query()
                ->when($this->status, function ($query) {
                    return $query->where('status', $this->status);
                })
                ->when($this->search, function ($query) {
                    return $query->where('reason', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ];
    }
}; ?>

<div>
    <input type="text" wire:model.debounce.300ms="search" placeholder="Search procurements...">
    
    <select wire:model="status">
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="partially_received">Partially Received</option>
        <option value="fully_received">Fully Received</option>
        <option value="completed">Completed</option>
    </select>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($procurements as $procurement)
                <tr>
                    <td>{{ $procurement->id }}</td>
                    <td>{{ $procurement->reason }}</td>
                    <td>{{ $procurement->status }}</td>
                    <td>{{ $procurement->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <button wire:click="selectProcurement({{ $procurement->id }})">View</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $procurements->links() }}
</div>