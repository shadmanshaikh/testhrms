<?php

use App\Models\Department;
use App\Models\Employeeinfo;
use App\Models\Interview;
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
    public array $nationalityChart = [
        'type' => 'bar',
        'data' => [
            'labels' => ['Indian' , 'British'],
            'datasets' => [
                [
                    'label' => 'Nationality',
                    'data' => [],
                    'borderColor' => 'rgb(232, 0, 50)',
                    'backgroundColor' => 'rgba(139, 1, 252, 0.5)',
                    'borderRadius' => 10,
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

        $employees = Employeeinfo::all();
        $groupedByNationality = $employees->groupBy('nationality');

        $labels = $groupedByNationality->keys()->toArray();
        $data = $groupedByNationality->map(function ($group) {
            return $group->count();
        })->toArray();

        Arr::set($this->nationalityChart, 'data.labels', $labels);
        Arr::set($this->nationalityChart, 'data.datasets.0.data', $data);

        // dd($this->upcomingInterviews);
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
    public function with(): array
    {
        return [
            'upcomingInterviews' => Interview::where('interviewDate', '>', now())->get(),
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
            <x-button icon="o-magnifying-glass" label="Search" class="btn-primary btn-sm" @click.stop="$dispatch('mary-search-open')" />
        </x-slot:middle>
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 overflow-hidden">
        <x-stat title="Employees" :value="$noEmployees" icon="o-user" tooltip="Welcome!" class="text-blue-500" />
        <x-stat title="Departments" :value="$departments" icon="o-building-office-2" tooltip-bottom="Great job!" class="text-green-500" />
        <x-stat title="Leads" :value="$leads" icon="o-archive-box" tooltip-left="Room for improvement" class="text-red-500" />
        @php $date = now()->format('d-m-Y'); @endphp
        <x-stat title="Date" :value="$date" icon="o-clock" tooltip-right="Knowledge is power!" class="text-purple-500" />
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
                <x-card class="lg:w-full" title="Nationality Distribution">
                    <x-chart wire:model="nationalityChart" style="width: 100%; height: auto;" />
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
   <div class="lg:grid grid-cols-2 gap-3 mt-3">
        <div class="col-span-1">
            <x-card class="w-full" title="Upcoming Interviews">
                    @if($upcomingInterviews->isEmpty())
                        <div class="text-center text-gray-500">No upcoming interviews</div>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($upcomingInterviews as $interview)
                                <li class="py-4 flex justify-between items-center">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium">{{$interview->interviewDate->format('d M Y')}}</span>
                                        <span class="text-sm text-gray-500">{{$interview->interviewTime}}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium">{{$interview->intervieweeName}}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

            </x-card>
        </div>
        <div class="col-span-1">
            <x-card class="w-full" title="">

            </x-card>
        </div>
   </div> 

</div>
