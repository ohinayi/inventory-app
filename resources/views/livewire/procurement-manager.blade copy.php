<?php

use Livewire\Volt\Component;
use App\Models\Procurement;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

new class extends Component {
    #[Url]    
    public $currentView = 'list';
     
    #[Url]    
    public $selectedProcurementId;

    #[Computed]
    public function selectedProcurement()
    {
        return Procurement::findOrFail($this->selectedProcurement);
    }

    protected $listeners = ['viewChanged', 'procurementSelected'];

    public function viewChanged($view)
    {
        $this->currentView = $view;
    }

    public function procurementSelected($procurementId)
    {
        // $this->selectedProcurement = Procurement::findOrFail($procurementId);
        $this->selectedProcurementId = Procurement::findOrFail($procurementId);
        $this->currentView = 'detail';
    }
}; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8" aria-label="Procurement Sections">
                <button 
                    wire:click="viewChanged('list')"
                    class="px-3 py-2 text-sm font-medium {{ $currentView === 'list' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                >
                    Procurement List
                </button>
                <button 
                    wire:click="viewChanged('form')"
                    class="px-3 py-2 text-sm font-medium {{ $currentView === 'form' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                >
                    New Procurement
                </button>
            </nav>
        </div>

        <!-- Dynamic Content Section -->
        <div class="bg-white rounded-lg shadow">
            @if($currentView === 'list')
                <div class="p-6">
                    <livewire:procurement-list />
                </div>
            @elseif($currentView === 'form')
                <div class="p-6">
                    <livewire:procurement-form />
                </div>
            @elseif($currentView === 'detail')
                @if($this->selectedProcurementId)
                    <div class="p-6">
                        <!-- Procurement Details Header -->
                        <div class="border-b border-gray-200 pb-4 mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">Procurement Details</h2>
                        </div>

                        <!-- Procurement Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Status</p>
                                <p class="text-lg font-medium">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                        {{ $this->selectedProcurement->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($this->selectedProcurement->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($this->selectedProcurement->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Reason</p>
                                <p class="text-lg font-medium">{{ $this->selectedProcurement->reason }}</p>
                            </div>
                        </div>

                        <!-- Procurement Items -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Requested Items</h3>
                            </div>
                            <div class="bg-gray-50 rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Requested</th>
                                            @if(!in_array($this->selectedProcurement->status, ['pending', 'approved']))
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Received</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($this->selectedProcurement->procurementItems as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $item->item->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $item->quantity_requested }}
                                                </td>
                                                @if(!in_array($this->selectedProcurement->status, ['pending', 'approved']))
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $item->quantity_received }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            {{ $item->quantity_received >= $item->quantity_requested ? 
                                                               'bg-green-100 text-green-800' : 
                                                               'bg-yellow-100 text-yellow-800' }}">
                                                            {{ $item->quantity_received >= $item->quantity_requested ? 
                                                               'Fully Received' : 'Partially Received' }}
                                                        </span>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Action Components -->
                        <div class="mt-6">
                            @if($this->selectedProcurement->status === 'pending')
                                <livewire:procurement-approval :procurement="$this->selectedProcurement" />
                            @elseif(in_array($this->selectedProcurement->status, ['approved', 'partially_received']))
                                <livewire:procurement-receiving :procurement="$this->selectedProcurement" />
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>