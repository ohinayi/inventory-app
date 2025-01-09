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

<div class="space-y-6">
    <!-- Filters Section -->
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                wire:model.debounce.300ms="search" 
                placeholder="Search procurements..."
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
        </div>
        <div class="sm:w-48">
            <select 
                wire:model="status"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="partially_received">Partially Received</option>
                <option value="fully_received">Fully Received</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($procurements as $procurement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $procurement->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $procurement->reason }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $procurement->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($procurement->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($procurement->status === 'partially_received' ? 'bg-blue-100 text-blue-800' : 
                                   ($procurement->status === 'fully_received' ? 'bg-purple-100 text-purple-800' : 
                                   'bg-gray-100 text-gray-800'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $procurement->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $procurement->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button 
                                wire:click="selectProcurement({{ $procurement->id }})"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                View
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $procurements->links() }}
    </div>
</div>