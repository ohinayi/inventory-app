<?php

use App\Models\Category;
use App\Models\Interest;
use App\Models\Item;
use App\Services\CategoryService;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Services\InterestService;
use Livewire\Attributes\Validate;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;
    
    #[Validate('required|string|unique:items,name')]
    public $name = '';

    #[Validate('required|numeric')]
    public $quantity = '';

    #[Validate('required|numeric')]
    public $default_limit;

    public function create(): void{
        // dump($this->thumb);
        if(request()->user()->can('create', Item::class)){
            $data =  $this->validate();
            $item = Item::query()->create($data);
            $this->dispatch('item-created', item: $item);
            $this->reset();
            $this->success('Successfull', 'Item Created Successfully', 'toast-top toast-center' );
        }else{
            
            $this->error('failed', 'You are not authorized', 'toast-top toast-center' );
            $this->cancelled();
        }

    }


    public function cancelled(): void{
        $this->dispatch('item-create-cancelled');
    }
}; ?>

<div>
<form wire:submit="create" class="p-7">
        <!-- Name -->
        <div class="mt-2">
            <x-mary-input wire:model="name" label="Item Name"  inline name='name' />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        
        <!-- Quantity -->
        <div  class="mt-4">
            <x-mary-input  wire:model="quantity" id="quantity" label="quantity" type="number" name="quantity" inline/>
            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
        </div>


        <!-- default_limit -->
         <div  class="mt-4">
            <x-mary-input  wire:model="default_limit" id="default_limit" label="Daily Limit" type="number" name="default_limit" inline/>
            <x-input-error :messages="$errors->get('default_limit')" class="mt-2" />
        </div>

        
        <div class="mt-5 actions">
            <x-primary-button class="ml-4">
                Create
            </x-primary-button>
    
            <x-secondary-button class="ml-4"  wire:click="cancelled">
                Close
            </x-secondary-button>

        </div>
        

       
           {{-- <x-secondary-button class="ml-4"  wire:click="$dispatch('cancelled')">
                Close
            </x-secondary-button>
        --}}
    </form>
</div>