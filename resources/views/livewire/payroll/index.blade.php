<?php

use Livewire\Volt\Component;
use App\Models\Employeeinfo;


new class extends Component {

    public bool $setDrawer = false;
    public $employee_id, $salary, $payment_date, $employees;
    public $selectedUser;
    public function mount()
    {
        // $this->employees = Employeeinfo::all();
    }

    public function addSalary()
    {
        // Logic to open the drawer
    }

    public function submitSalary()
    {
        // Logic to submit the salary form
        // You can use the $employee_id, $salary, and $payment_date properties here
    }


    public function with(): array
    {
        return [
            'title' => 'Payroll',
            'icon' => 'o-money',
            'link' => '/payroll',
        ];
    }
}; ?>

<div>
    <x-header :title="$title" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" class="lg:w-auto w-full" />
        </x-slot:middle>
    </x-header>
    <div class="flex justify-end">
        <x-button class="btn-sm btn-primary" icon="o-plus" label="Salary" wire:click="$toggle('setDrawer')" />
    </div>
    <x-drawer wire:model="setDrawer" class="w-11/12 lg:w-1/3" right>
        <x-form wire:submit="submitSalary">
            <div>
                @php 
                    $emp = App\Models\User::all();
                @endphp        

                <label for="selectedUser" class="block text-sm font-medium text-gray-700">Select Employee</label>
                <x-select wire:model="selectedUser" :options="$emp" placeholder="Select Employee" />

                <div class="mt-4">
                    <label for="salary" class="block text-sm font-medium text-gray-700">Base Salary</label>
                    <x-input wire:model="salary" id="salary" type="text" class="mt-1 block w-full" placeholder="Enter base salary" />
                </div>
                
                <div class="mt-4">
                    <label for="joining_date" class="block text-sm font-medium text-gray-700">Joining Date</label>
                    <x-input wire:model="joining_date" id="joining_date" type="date" class="mt-1 block w-full" placeholder="Select joining date" />
                </div>
                    
            </div>
            <x-button type="submit" class=" btn-sm btn-primary mt-4">Submit</x-button>
        </x-form>
    </x-drawer>

    
</div>
