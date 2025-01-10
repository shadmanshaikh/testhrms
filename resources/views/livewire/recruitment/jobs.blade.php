<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Jobpost;
use App\Models\Department;



new class extends Component {
    use Toast;

    public string $search = '';
    public bool $showDrawer2 = false;
    public bool $drawer = false;
    public $department , $jobRole , $jobDescription , $deadline , $jobpostdate , $salary;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public $jobPosts;
    // Clear filters
    public function mount(){
          $this->jobPosts = Jobpost::all();
    }
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

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'age', 'label' => 'Age', 'class' => 'w-20'],
            ['key' => 'email', 'label' => 'E-mail', 'sortable' => false],
        ];
    }

    /**
     * For demo purpose, this is a static collection.
     *
     * On real projects you do it with Eloquent collections.
     * Please, refer to maryUI docs to see the eloquent examples.
     */
    public function users(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Mary', 'email' => 'mary@mary-ui.com', 'age' => 23],
            ['id' => 2, 'name' => 'Giovanna', 'email' => 'giovanna@mary-ui.com', 'age' => 7],
            ['id' => 3, 'name' => 'Marina', 'email' => 'marina@mary-ui.com', 'age' => 5],
        ])
            ->sortBy([[...array_values($this->sortBy)]])
            ->when($this->search, function (Collection $collection) {
                return $collection->filter(fn(array $item) => str($item['name'])->contains($this->search, true));
            });
    }

    public function saveJobPost(){
        $jobpost = new Jobpost;
        $jobpost->department = Department::where('id', $this->department)->first()->name ?? null;
        $jobpost->job_role = $this->jobRole;
        $jobpost->jd = $this->jobDescription;
        $jobpost->salary = $this->salary;
        $jobpost->deadline = $this->deadline;
        $jobpost->date_posted = now();
        $jobpost->save();
        $this->success('Job post saved successfully.', position: 'toast-bottom');
        $this->showDrawer2 = false;
        $this->redirect('/jobs');
    }

    public function with(): array
    {
        return [
            'users' => $this->users(),
            'headers' => $this->headers()
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Job Posting" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>
    </x-header>

    <div class="flex justify-end">
        <x-button class="btn-sm btn-primary mb-3" icon="o-plus" label="Job Post" wire:click="$toggle('showDrawer2')" />
    </div>
    <!-- TABLE  -->

    

    <x-drawer wire:model="showDrawer2" class="w-11/12 lg:w-1/3" right>
        <x-form wire:submit="saveJobPost">
        <div>
            <div>
                @php 
                    $departments = \App\Models\Department::all();
                @endphp
                <div class="mb-3">
                    <x-select placeholder="Department" wire:model="department" :options="$departments" />
                </div>
            </div>
            <div class="mb-3">
                <x-input placeholder="Job Role" wire:model="jobRole" icon="o-briefcase" />
            </div>
            <div class="mb-3">
                <x-textarea placeholder="Job Description" wire:model="jobDescription" icon="o-file-text" />
            </div>
            <div class="mb-3">
                <x-input placeholder="Salary" wire:model="salary" icon="o-calculator" />
            </div>
            <div class="mb-3">
                <x-datepicker placeholder="Deadline" wire:model="deadline" icon="o-calendar" />
            </div>
        </div>

            <x-slot:actions>
                <x-button label="Close" @click="$wire.showDrawer2 = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>


    <div class="lg:grid grid-cols-3 gap-4 md:grid grid-cols-1 gap-4 mb-2">
        @foreach($jobPosts as $jobPost)
            <x-card class="shadow-md hover:shadow-lg transition duration-300 ease-in-out">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-blue-900">{{ $jobPost->job_role }}</h3>
                    <p class="text-sm text-gray-600">{{ $jobPost->department }}</p>
                    <p class="text-sm text-gray-600">Salary: {{ $jobPost->salary }}</p>
                    <p class="text-sm text-gray-600">Deadline: {{ $jobPost->deadline }}</p>
                    <div class="flex justify-end mt-4">
                        <x-button label="View Details" class="btn-sm btn-primary" />
                        <x-button label="Apply Now" class="btn-sm btn-success ml-2" />
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
