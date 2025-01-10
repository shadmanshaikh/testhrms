<?php

use Livewire\Volt\Component;

use App\Models\Employeeinfo;
use Illuminate\Support\Collection;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;
    
    public $name;
    public $search = '';
    public $drawer = false;

    public function mount($name)
    {
        $this->name = $name;
    }

    public function emp() : Collection {
        return Employeeinfo::where('name', $this->name)
            ->when($this->search, function($query) {
                return $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->get();
    }

    public function with(): array
    {
        return [
            'empD' => $this->emp(),
        ];
    }

    public function clear()
    {
        $this->search = '';
        $this->drawer = false;
    }
};
?>

<div>
    <!-- HEADER -->
    <x-header title="Employee Details" separator progress-indicator class="mb-4">
        <x-slot:middle class="flex justify-end items-center">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" class="w-48" />
        </x-slot:middle>
        <x-slot:actions class="flex items-center">
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" class="mr-2" />
        </x-slot:actions>
    </x-header>

    <!-- Employee Details -->
    @foreach($empD as $employee)
    <x-card class="mb-6 hover:shadow-xl transition-shadow duration-300">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4">
            <!-- Profile Section -->
            <div class="md:col-span-1 flex flex-col items-center justify-center p-4 border-r border-gray-200">
                <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                    <x-icon name="o-user" class="w-12 h-12 text-primary" />
                </div>
                <h3 class="font-bold text-xl text-gray-800">{{$employee->name}} {{$employee->lastname}}</h3>
                <p class="text-primary font-medium">{{$employee->designation}}</p>
            </div>

            <!-- Details Section -->
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <x-icon name="o-identification" class="w-5 h-5 text-gray-500" />
                        <div>
                            <p class="text-sm text-gray-500">Employee ID</p>
                            <p class="font-medium">{{$employee->employee_id}}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-icon name="o-envelope" class="w-5 h-5 text-gray-500" />
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{$employee->email}}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-icon name="o-phone" class="w-5 h-5 text-gray-500" />
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-medium">{{$employee->phone}}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <x-icon name="o-building-office" class="w-5 h-5 text-gray-500" />
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            @php
                                $department = App\Models\Department::find($employee->department_id);
                            @endphp
                            <p class="font-medium">{{$department->name}}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-icon name="o-calendar" class="w-5 h-5 text-gray-500" />
                        <div>
                            <p class="text-sm text-gray-500">Joining Date</p>
                            <p class="font-medium">{{$employee->joining_date}}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-icon name="o-briefcase" class="w-5 h-5 text-gray-500" />
                        <div>
                            <p class="text-sm text-gray-500">Employment Type</p>
                            
                            <p class="font-medium">{{$employee->employment_type}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
    <x-card class="mb-6 hover:shadow-xl transition-shadow duration-300">
        <div class="p-6">
            <h3 class="text-xl font-semibold mb-4">Employee Documents</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if ($employee->emirates_id)
                    <div>
                        <h4 class="font-medium">Emirates ID</h4>
                        <img src="{{ asset('storage/' . $employee->emirates_id) }}" alt="Emirates ID" class="rounded-lg shadow-md">
                    </div>
                @endif
                @if ($employee->work_permit)
                    <div>
                        <h4 class="font-medium">Work Permit</h4>
                        <img src="{{ asset('storage/' . $employee->work_permit) }}" alt="Work Permit" class="rounded-lg shadow-md">
                    </div>
                @endif
                @if ($employee->certificates)
                    <div>
                        <h4 class="font-medium">Certificates</h4>
                        <img src="{{ asset('storage/' . $employee->certificates) }}" alt="Certificates" class="rounded-lg shadow-md">
                    </div>
                @endif
                @if ($employee->police_clearance)
                    <div>
                        <h4 class="font-medium">Police Clearance</h4>
                        <img src="{{ asset('storage/' . $employee->police_clearance) }}" alt="Police Clearance" class="rounded-lg shadow-md">
                    </div>
                @endif
            </div>
        </div>
    </x-card>

    @endforeach

    <!-- FILTER DRAWER -->

</div>
