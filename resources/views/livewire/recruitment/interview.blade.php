<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Interview;


new class extends Component {
    use Toast;

    public string $search = '';
    public bool $showInterviewDetails = false;
    public bool $drawer = false;
    public string $intervieweeName = '';
    public string $interviewDate = '';
    public string $interviewTime = '';
    public string $interviewDetails = '';

    public $users;
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
        Interview::find($id)->delete();
        $this->success("Interview #$id deleted successfully.", position: 'toast-bottom');
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
    public function mount(){
        $this->users = Interview::all();
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
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com', 'age' => 30],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'age' => 28],
            ['id' => 3, 'name' => 'Michael Johnson', 'email' => 'michael.johnson@example.com', 'age' => 35],
        ])
            ->sortBy([[...array_values($this->sortBy)]])
            ->when($this->search, function (Collection $collection) {
                return $collection->filter(fn(array $item) => str($item['name'])->contains($this->search, true));
            });
    }
    public function saveInterviewDetails(): void
    {
        $validatedData = $this->validate([
            'intervieweeName' => 'required|string',
            'interviewDate' => 'required|date',
            'interviewTime' => 'required|date_format:H:i',
            'interviewDetails' => 'required|string',
        ]);

        Interview::create($validatedData);

        $this->reset(['intervieweeName', 'interviewDate', 'interviewTime', 'interviewDetails']);
        $this->showInterviewDetails = false;

        $this->success('Interview details saved successfully.', position: 'toast-bottom');
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
    <x-header title="Interviews Scheduled" separator progress-indicator class="mb-4">
        <x-slot:middle class="flex justify-end items-center">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" class="w-48" />
        </x-slot:middle>
        <x-slot:actions class="flex items-center">
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" class="mr-2" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <div class="mb-3 flex justify-end">
        <x-button icon="o-plus" label="Schedule Interview" @click="$wire.showInterviewDetails = true" class="btn-primary" />
    </div>

    <x-drawer wire:model="showInterviewDetails" class="w-11/12 lg:w-1/3" right>
        <x-form wire:submit="saveInterviewDetails" class="p-4">
            <div class="mt-4">
                <x-input icon="o-user" class="form-input" label="Interviewee Name" wire:model="intervieweeName" placeholder="Enter interviewee name" />
            </div>
            <div class="mt-4">
                <x-input icon="o-calendar" class="form-input" label="Interview Date" wire:model="interviewDate" type="date" placeholder="Select interview date" />
            </div>
            <div class="mt-4">
                <x-input icon="o-clock" class="form-input" label="Interview Time" wire:model="interviewTime" type="time" placeholder="Select interview time" />
            </div>
            <div class="mt-4">
                <x-textarea icon="o-comment" class="form-textarea" label="Interview Details" wire:model="interviewDetails" placeholder="Enter interview details" />
            </div>
            <x-slot:actions class="mt-4 flex justify-end">
                <x-button label="Close" @click="$wire.showInterviewDetails = false" class="mr-2" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($users as $user)
            <x-card class="shadow-lg rounded-lg bg-white p-4">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $user['intervieweeName'] }}</h3>
                    <p class="text-sm text-gray-600">Date: {{ $user['interviewDate'] }}</p>
                    <p class="text-sm text-gray-600">Time: {{ $user['interviewTime'] }}</p>
                    <p class="text-sm text-gray-600">Details: {{ $user['interviewDetails'] }}</p>
                    <div class="absolute bottom-0 right-0 m-4">
                        <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-500" />
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" class="mt-4 mb-4" />

        <x-slot:actions class="mt-4 flex justify-end">
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner class="mr-2" />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
