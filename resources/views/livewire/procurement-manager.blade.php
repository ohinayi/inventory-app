<?php

use Livewire\Volt\Component;
use App\Models\Procurement;

new class extends Component {
    public $currentView = 'list';
    public $selectedProcurement;

    protected $listeners = ['viewChanged', 'procurementSelected'];

    public function viewChanged($view)
    {
        $this->currentView = $view;
    }

    public function procurementSelected($procurementId)
    {
        $this->selectedProcurement = Procurement::findOrFail($procurementId);
        $this->currentView = 'detail';
    }
}; ?>

<div>
    <livewire:procurement-form />


    @if($currentView === 'list')
        <livewire:procurement-list />
    @elseif($currentView === 'form')
        <livewire:procurement-form />
    @elseif($currentView === 'detail')
        @if($selectedProcurement)
            <h2>Procurement Details</h2>
            <p>Status: {{ $selectedProcurement->status }}</p>
            <p>Reason: {{ $selectedProcurement->reason }}</p>

            @if($selectedProcurement->status === 'pending')
                <livewire:procurement-approval :procurement="$selectedProcurement" />
            @elseif(in_array($selectedProcurement->status, ['approved', 'partially_received']))
                <livewire:procurement-receiving :procurement="$selectedProcurement" />
            @endif
        @endif
    @endif
</div>
