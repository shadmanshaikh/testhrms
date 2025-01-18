<?php

use App\Models\Department;
use App\Models\Employeeinfo;
use App\Models\Lead;
use Livewire\Volt\Component;
use Illuminate\Support\Arr; 
use Illuminate\Support\Collection;


new class extends Component {


    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public string $search = '';
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'employee_id', 'label' => 'EMP ID', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'email', 'label' => 'E-mail', 'sortable' => false],
        ];
    }
    public array $lineChart = [
        'type' => 'line',
        'data' => [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => [65, 59, 80, 81, 56, 55, 40],
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                ],
                [
                    'label' => 'Expenses',
                    'data' => [28, 48, 40, 19, 86, 27, 90],
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                ]
            ]
        ],
        'options' => [
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false
                    ]
                ],
                'y' => [
                    'grid' => [
                        'display' => false
                    ]
                ]
            ]
        ]
    ];
    public array $myChart = [
        'type' => 'pie',
        'data' => [
            'labels' => ['Male', 'Female'],
            'datasets' => [
                [
                    'label' => '# of Votes',
                    'data' => [12, 19, 3],
                ]
            ]
        ]
    ];

    public function mount(){
        $male = Employeeinfo::where('gender', 1)->count();
        $female = Employeeinfo::where('gender', 2)->count();
        Arr::set($this->myChart, 'data.datasets.0.data', [$male, $female]);
    }
    public function users(): Collection
    {
        $emp = \App\Models\Employeeinfo::all();
        return $emp
            ->sortBy([[...array_values($this->sortBy)]])
            ->when($this->search, function (Collection $collection) {
                return $collection->filter(fn(array $item) => str($item['name'])->contains($this->search, true));
            });
    }
    public function randomize()
    {
    }
    public function with(): array
    {
        return [
            'noEmployees' => Employeeinfo::count(),
            'departments'=>Department::count(),
            'leads' => Lead::count(),
            'title' => 'Dashboard',
            'icon' => 'o-chart-pie',
            'link' => '/',
            'users' => $this->users(),
            'headers' => $this->headers(),
        ];
    }
}; ?>

<div>
    
    <x-header :title="$title" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" class="lg:w-auto w-full" />
        </x-slot:middle>
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 overflow-hidden">
        <x-stat title="Employees" :value="$noEmployees" icon="o-user" tooltip="Welcome!" class="text-blue-500" />
        <x-stat title="Departments" :value="$departments" icon="o-building-office-2" tooltip-bottom="Great job!" class="text-green-500" />
        <x-stat title="Leads" :value="$leads" icon="o-archive-box" tooltip-left="Room for improvement" class="text-red-500" />
        <x-stat title="Training Sessions" description="This quarter" value="20" icon="o-book-open" tooltip-right="Knowledge is power!" class="text-purple-500" />
    </div>
    
    <div class="lg:grid grid-cols-2 mt-3 gap-3 md:grid grid-cols-1 mt-3 gap-3">
            <div class="col-span-1">
                <x-card class="w-full" title="Gender Difference">
                    <div class="flex justify-center">
                        <x-chart wire:model="myChart" style="width: 50%; height: auto;" />
                    </div>
                </x-card>
            </div>
            <div class="col-span-1">
                <x-card class="lg:w-full" title="Sales Vs Expense">
                    <x-chart wire:model="lineChart" style="width: 100%; height: auto;" />
                </x-card>
            </div>
    </div>
<div class="col-span-1">
<x-card class="shadow-lg rounded-lg  p-4 mt-3" title="All Employees">
    <x-slot:menu>
        <x-button label="All Employees" class="btn-sm" link="/employee" icon="o-arrow-long-right"  />
    </x-slot:menu>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" link="/employee/{name}">
    
        </x-table>
    </x-card>
</div>
    

</div>
