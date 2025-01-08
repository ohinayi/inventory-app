<?php

use Filament\Tables\Table;


use App\Models\Employee;
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
    public ?Employee $model=null;
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

    public function setEmployee(Employee $employee){
        $this->model = $employee;
        $this->dispatch('employee-changed', employee: $employee);
        $this->dispatch('open-modal', 'model-edit');

    }

    protected function getQuery(): Builder
    {
        return Employee::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy(...array_values($this->sortBy));
    }

    #[On('employee-created')]
    public function handleEmployeeCreated($employee)
    {
        $this->resetQueries();
        $this->closeCreateModal($employee);
    }

    #[On('employee-updated')]
    public function handleEmployeeUpdated($employee)
    {
        $this->resetQueries();
        if ($employee !== null) {
            $this->search = $employee['name'];
        }
    }

    #[On('employee-create-cancelled')]
    public function handleEmployeeCreateCancelled()
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
            'employees' => $this->getQuery()->paginate(),
        ];
    }
    
}; 






?>

<div class="relative p-6 overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex items-center gap-4">
        <x-mary-input clearable label="Search" placeholder="Search a employee" icon="o-magnifying-glass" hint="Start typing the employee name to search" wire:model.live="search" />
        <button wire:click="openCreateModal" type="button" class="btn btn-outline btn-primary">Create Employee</button>
        <button wire:click="resetQueries " type="button" class="btn btn-outline btn-primary">Reset Queries </button>
        <div class="flex gap-4">

        </div>
    </div>
    @php
    $headers = [
    ['key' => 'id', 'label' => '#'],
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'created_at', 'label' => 'created']
    ];
    @endphp
    <x-mary-table :headers="$headers" :rows="$employees" striped @row-click="$wire.setEmployee($event.detail.id)" :sort-by="$sortBy" with-pagination>

        @scope('actions', $employee)
        <div class="flex gap-1">

            <x-mary-button icon="o-pencil" wire:click="setEmployee({{ $employee->id }})" spinner class="btn-sm" />
            {{-- <x-mary-button icon="o-trash" wire:click="delete({{ $employee->id }})" spinner class="btn-sm" /> --}}

        </div>
        @endscope

    </x-mary-table>

    <x-modal name name="model-create" :show="$this->creating">
        <livewire:keeper.employees.create-employee />
    </x-modal>

    <!-- Edit interest modal -->
    <x-modal name="model-edit" :show="$this->hasModel" focusable>

        @if ($this->model!=null)
        <livewire:keeper.employees.edit-employee :employee="$this->model" />
        @endif
    </x-modal>




</div>