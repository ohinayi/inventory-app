<?php

use Mary\Traits\Toast;
use App\Models\Employee;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    use  Toast;

    public Employee $employee;



    // #[Validate('required|string|unique:categories,name')]
    // public $name = '';

    // #[Validate('required|numeric')]
    // public $quantity = '';

    // #[Validate('required|numeric')]
    // public $default_limit;

    // public function changeAvatar()
    // {
    //     if (request()->user()->can('update', $this->employee)) {
    //         // $this->authorize('update', $this->employee); 
    //         $data =  $this->validate();
    //         // Validator::make(['avatar' => $this->avatar], ['avatar' => 'required|image'])->validate();
    //         $this->employee->update($data);
    //         $this->refreshEmployee();
    //         $this->success('Successfull', 'Employee updated Successfully', 'toast-top toast-center' );
    //     } else {
            
    //         $this->error('failed', 'You are not authorized', 'toast-top toast-center');
    //         $this->dispatch('close');
    //     }
    // }

    #[On('employee-changed')]
    public function changeEmployee(Employee $employee)
    {
        $this->employee = $employee;
        $this->reasonForRequest = '';
        $this->dispatch('reset-form', attrs: $employee);
    }

    #[On('model-updated')]
    public function refreshEmployee()
    {
        $this->employee->refresh();
        $this->dispatch('employee-updated', employee: $this->employee);
    }
    // public function requestDelete()
    // {
    //     if (request()->user()->can('create', ActionRequest::class) && request()->user()->can('update', $this->employee)) {

    //         Validator::make(['reason_for_request' => $this->reasonForRequest], ['reason_for_request' => 'required|string'])->validate();
    //         $requests = $this->employee->ActionRequests()->where('user_id', auth()->id())->where('type', 'delete')->where('status', 1)->count();
    //         if($requests>0){
    //             $this->error('failed', 'You have a pending request', 'toast-top toast-center');
    //             return;
    //         }
    //         $this->employee->ActionRequests()->create([
    //             'description' => $this->reasonForRequest,
    //             'user_id' => auth()->id(),
    //             'type' => 'delete',
    //             'action' => EmployeeResource::getUrl(),
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
            Name: {{ $this->employee->name  }}

        </h2>



        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Created at: {{ $this->employee->created_at  }}. ||
            Updated at: {{ $this->employee->updated_at }}
        </p>

        <livewire:edit-model-attribute class="border rounded-md" attribute="name" :model="$this->employee" rules='required|string|unique:employees,name' />




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