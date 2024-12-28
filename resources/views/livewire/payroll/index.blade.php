<?php

use Livewire\Volt\Component;
use App\Models\Employeeinfo;
use App\Models\Setsalary;
use Mary\Traits\Toast;
use Illuminate\Support\Collection;


new class extends Component {
    use Toast;
    public bool $setDrawer = false;
    public $selectedUser , $baseSalary ,  $joiningDate;
    public string $search = '';

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Delete action
    public function delete($id): void
    {
        $this->warning("Will delete #$id", 'It is fake.', position: 'toast-bottom');
    }
    public function headers(): array
    {
        return [
            ['key' => 'emp_id', 'label' => 'EMP ID', 'class' => 'w-1', 'sortable' => true, 'bold' => true],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-20', 'sortable' => true, 'bold' => true],
            ['key' => 'base', 'label' => 'Base Salary', 'class' => 'w-20', 'sortable' => true, 'bold' => true],
            ['key' => 'joiningdate', 'label' => 'Joining Date', 'class' => 'w-20', 'sortable' => true, 'bold' => true],
        ];
    }

    public function users(): Collection
    {
        $payroll = Setsalary::all();
        return $payroll
            ->sortBy([[...array_values($this->sortBy)]])
            ->when($this->search, function (Collection $collection) {
                return $collection->filter(fn(array $item) => str($item['name'])->contains($this->search, true));
            });
    }

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
        $empID = Employeeinfo::where('id' , $this->selectedUser)->value('employee_id');
        $empName = Employeeinfo::where('id' , $this->selectedUser)->value('name');
        // dd($empID);
        Setsalary::create([
            'emp_id' => $empID,
            'name' => $empName,
            'joiningdate' => $this->joiningDate,
            'base' => $this->baseSalary
        ]);
        $this->success('successfully saved');
        $this->selectedUser = '';
        $this->baseSalary = '';
        // $this->joiningDate = '';
    }


    public function with(): array
    {
        return [
            'title' => 'Payroll',
            'icon' => 'o-money',
            'link' => '/payroll',
            'users' => $this->users(),
            'headers' => $this->headers()
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
                    $emp = App\Models\Employeeinfo::all();
                @endphp        

                <label for="selectedUser" class="block text-sm font-medium text-gray-700">Select Employee</label>
                <x-select wire:model="selectedUser" :options="$emp" placeholder="Select Employee" />

                <div class="mt-4">
                    <label for="salary" class="block text-sm font-medium text-gray-700">Base Salary</label>
                    <x-input wire:model="baseSalary" id="baseSalary" type="text" class="mt-1 block w-full" placeholder="Enter base salary" />
                </div>
                
                <div class="mt-4">
                    <label for="joining_date" class="block text-sm font-medium text-gray-700">Joining Date</label>
                    <x-datepicker label="Date" wire:model="joiningDate" icon="o-calendar"  />
                </div>
                    
            </div>
            <x-slot:actions>
            <x-button label="Cancel" />
            <x-button label="Click me!" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
        </x-form>
    </x-drawer>


        <x-card class="mt-3">
            <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy">

            </x-table>
        </x-card>

    
</div>
