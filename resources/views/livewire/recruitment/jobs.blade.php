<?php

use App\Models\Getjobs;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {
    use Toast;
    use WithPagination;
    public $title = '';
    public $department = '';
    public $type = '';
    public $location = '';
    public $description = '';
    public $requirements = '';
    public $salary_range = '';
    public $status = '';
    public $deadline = '';
    public bool $drawer = false;

    public array $sortBy = ['column' => 'title', 'direction' => 'asc'];
    public string $search = '';

    public function clear(): void
    {
        $this->reset();
    }

    public function saveJob()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string',
            'type' => 'required|string',
            'location' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary_range' => 'required|string',
            'status' => 'required|string',
            'deadline' => 'required|date'
        ]);

        Getjobs::create($validated);
        $this->success('Job posting created successfully');
        $this->reset(['title', 'department', 'type', 'location', 'description', 'requirements', 'salary_range', 'status', 'deadline']);
        $this->dispatch('close-drawer', 'add-job-drawer');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'title', 'label' => 'Job Title', 'class' => 'w-64'],
            ['key' => 'department', 'label' => 'Department', 'class' => 'w-48'],
            ['key' => 'type', 'label' => 'Job Type', 'class' => 'w-32'],
            ['key' => 'location', 'label' => 'Location', 'class' => 'w-48'],
            ['key' => 'salary_range', 'label' => 'Salary Range', 'class' => 'w-32'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-24'],
            ['key' => 'deadline', 'label' => 'Deadline', 'class' => 'w-32'],
        ];
    }

    public function data(): LengthAwarePaginator {
        return Getjobs::query()
            ->when($this->sortBy['column'], function ($query) {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('department', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    public function with(): array
    {
        return [
            'data' => $this->data(),
            'headers' => $this->headers()
        ];
    }
};

?>

<div>
    <x-header title="Job Postings">
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>
    </x-header>

    <div class="text-end mb-4 flex justify-end">
        <label for="add-job-drawer" class="btn btn-primary btn-sm">
            <x-icon name="o-plus" class="lg:w-5 h-5 me-2" /> Add Job Posting
        </label>
    </div>

    <x-card>
        <x-form wire:submit="saveJob">
            <x-drawer id="add-job-drawer" title="Add New Job Posting" class="lg:w-1/2" seperator right>
                <div class="space-y-4">
                    <x-input label="Job Title" wire:model="title" required />
                    
                    <x-input label="Department" wire:model="department" required />
                    
                    <x-input label="Job Type" wire:model="type" required />
                    
                    <x-input label="Location" wire:model="location" required />
                    
                    <x-textarea label="Job Description" wire:model="description" required />
                    
                    <x-textarea label="Requirements" wire:model="requirements" required />
                    
                    <x-input label="Salary Range" wire:model="salary_range" required />
                    
                    <x-input label="Status" wire:model="status" required />
                    
                    <x-input type="date" label="Application Deadline" wire:model="deadline" required />

                    <x-slot:actions>
                        <x-button label="Cancel" class="btn-ghost" @click="$dispatch('close-drawer', 'add-job-drawer')" />
                        <x-button label="Post Job" class="btn-primary" type="submit" />
                    </x-slot:actions>
                </div>
            </x-drawer>
        </x-form>

        <x-table :headers="$headers" :rows="$data">
        </x-table>
    </x-card>

    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
