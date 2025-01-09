<?php

use Mary\Traits\Toast;
use App\Models\Item;
use App\Models\Interest;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Services\ItemService;
use App\Services\InterestService;
use Illuminate\Support\Facades\Validator;
use App\Filament\Resources\ItemResource;
use App\Filament\Resources\InterestResource;
use App\Models\ActionRequest;
use Filament\Actions\Action;
use Livewire\Attributes\Validate;

new class extends Component
{
    use  Toast;

    public Item $item;



    // #[Validate('required|string|unique:categories,name')]
    // public $name = '';

    // #[Validate('required|numeric')]
    // public $quantity = '';

    // #[Validate('required|numeric')]
    // public $default_limit;

    // public function changeAvatar()
    // {
    //     if (request()->user()->can('update', $this->item)) {
    //         // $this->authorize('update', $this->item); 
    //         $data =  $this->validate();
    //         // Validator::make(['avatar' => $this->avatar], ['avatar' => 'required|image'])->validate();
    //         $this->item->update($data);
    //         $this->refreshItem();
    //         $this->success('Successfull', 'Item updated Successfully', 'toast-top toast-center' );
    //     } else {
            
    //         $this->error('failed', 'You are not authorized', 'toast-top toast-center');
    //         $this->dispatch('close');
    //     }
    // }

    #[On('item-changed')]
    public function changeItem(Item $item)
    {
        $this->item = $item;
        $this->reasonForRequest = '';
        $this->dispatch('reset-form', attrs: $item);
    }

    #[On('model-updated')]
    public function refreshItem()
    {
        $this->item->refresh();
        $this->dispatch('item-updated', item: $this->item);
    }
    // public function requestDelete()
    // {
    //     if (request()->user()->can('create', ActionRequest::class) && request()->user()->can('update', $this->item)) {

    //         Validator::make(['reason_for_request' => $this->reasonForRequest], ['reason_for_request' => 'required|string'])->validate();
    //         $requests = $this->item->ActionRequests()->where('user_id', auth()->id())->where('type', 'delete')->where('status', 1)->count();
    //         if($requests>0){
    //             $this->error('failed', 'You have a pending request', 'toast-top toast-center');
    //             return;
    //         }
    //         $this->item->ActionRequests()->create([
    //             'description' => $this->reasonForRequest,
    //             'user_id' => auth()->id(),
    //             'type' => 'delete',
    //             'action' => ItemResource::getUrl(),
    //         ]);
    //         $this->success('Successfull', 'Request Sent Successfully', 'toast-top toast-center' );

    //         // session()->flash('status', 'Request Sent Successfully');
    //     } else {

    //         $this->error('failed!!', 'You are not authorized', 'toast-top toast-center');
    //         $this->dispatch('close');
    //     }
    // }
}; ?>

<div>
    <div class="p-6">

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Name: {{ $this->item->name  }}

        </h2>

        <p class="mt-1 overflow-hidden text-sm text-gray-600 dark:text-gray-400 text-ellipsis">
            Quantity Remaining: {{ $this->item->quantity  }}
        </p>

        <p class="mt-1 overflow-hidden text-sm text-gray-600 dark:text-gray-400 text-ellipsis">
            Default Daily Limit: {{ $this->item->default_limit  }}
        </p>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Created at: {{ $this->item->created_at  }}. ||
            Updated at: {{ $this->item->updated_at }}
        </p>

        <livewire:edit-model-attribute class="border rounded-md" attribute="name" :model="$this->item" rules='required|string|unique:items,name' />
       {{-- <livewire:edit-model-attribute class="border rounded-md" attribute="quantity" :model="$this->item" rules='numeric' input_type='number' />
        --}} <livewire:edit-model-attribute class="border rounded-md" attribute="default_limit" :model="$this->item" rules='numeric' input_type='number' />





        <!-- request delete -->
        <div class="flex flex-wrap items-end gap-1 p-4 border rounded-md">
            <!-- <p class="w-full text-xs" >ds</p> -->
            <x-input-error :messages="$errors->get('reason_for_request')" class="w-full mt-2" />
            <div>
                <label for="reasonForRequest" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reason For Request</label>
                <input type="text" id="reasonForRequest" wire:model="reasonForRequest" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
            </div>
            <x-danger-button class="ml-3" wire:click="requestDelete">
               Request for delete
            </x-danger-button>
        </div>
        <!-- request delete -->

        <!-- utility buttons -->
        <div class="flex flex-wrap gap-1 my-3">

        </div>
        <!-- utility buttons -->










        <div class="flex justify-end mt-6">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>


        </div>
    </div>
</div>