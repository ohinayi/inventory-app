<?php

use Livewire\Volt\Component;
use App\Models\Procurement;

new class extends Component {
    public Procurement $procurement;

    public function approve()
    {
        // $this->authorize('approve', $this->procurement);

        $this->procurement->update([
            'status' => 'approved',
            'approved_by_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        $this->procurement->procurementItems()->update(['is_approved' => true]);

        $this->dispatch('procurementApproved');
    }

    public function reject()
    {
        $this->authorize('approve', $this->procurement);

        $this->procurement->update([
            'status' => 'rejected',
            'approved_by_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        $this->dispatch('procurementRejected');
    }
}; ?>

<div>
    <h3>Approve or Reject Procurement</h3>
    <button wire:click="approve">Approve</button>
    <button wire:click="reject">Reject</button>
</div>
