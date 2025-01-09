<?php


use App\Models\User;
use Livewire\Volt\Component;

use Livewire\Attributes\Validate;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Hash;


new class extends Component {
    use Toast;

    #[Validate('required|string|unique:users,name')]
    public $name = '';

    #[Validate('required|string|unique:users,email')]
    public $email = '';


    public function create(): void{
        // dump($this->thumb);
        if(request()->user()->can('create', User::class)){
            $data =  $this->validate();
            $user = User::query()->create(['password'=> Hash::make('12345678') ,... $data]);
            $this->dispatch('user-created', user: $user);
            $this->reset();
            $this->success('Successfull', 'user Created Successfully', 'toast-top toast-center' );
        }else{

            $this->error('failed', 'You are not authorized', 'toast-top toast-center' );
            $this->cancelled();
        }

    }


    public function cancelled(): void{
        $this->dispatch('user-create-cancelled');
    }
}; ?>

<div>
<form wire:submit="create"  class="p-7">
        <!-- Name -->
        <div class="mt-2">
            <x-mary-input wire:model="name" label="Name"  inline name='name' />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

         <!-- Error -->
         <div class="mt-2">
            <x-mary-input wire:model="email" label="Email"  inline name='email' />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
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
