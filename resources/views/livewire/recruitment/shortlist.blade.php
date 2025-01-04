<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Shortlist;


new class extends Component {
    use Toast;

    public string $search = '';
    public bool $showDrawer2 = false;
    public bool $drawer = false;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $status = '';

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

    // Table headers

    public function saveShortlist(){
        $shortlist = new Shortlist;
        $shortlist->name = $this->name;
        $shortlist->email = $this->email;
        $shortlist->phone = $this->phone;
        $shortlist->status = $this->status;
        $shortlist->save();
        $this->success('Shortlist saved successfully.', position: 'toast-bottom');
    }   

    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'phone', 'label' => 'Phone', 'class' => 'w-64'],
            ['key' => 'email', 'label' => 'E-mail', 'class' => 'w-64', 'sortable' => false],
            ['key' => 'jobrole', 'label' => 'Job Role', 'class' => 'w-64', 'sortable' => false],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-32'],
        ];
    }

    public function users(): Collection
    {
        $data = Shortlist::all();
        return $data
            ->sortBy([[...array_values($this->sortBy)]])
            ->when($this->search, function (Collection $collection) {
                return $collection->filter(function ($item) {
                    return str($item->name)->contains($this->search, true);
                });
            });
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
    <x-header title="Shortlisted Candidates" separator progress-indicator class="mb-4">
        <x-slot:middle class="flex justify-end items-center">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" class="w-48" />
        </x-slot:middle>
        <x-slot:actions class="flex items-center">
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" class="mr-2" />
        </x-slot:actions>
    </x-header>

    <div class="mb-3 flex justify-end">
        <x-button icon="o-plus" label="Add to Shortlist" wire:click="$toggle('showDrawer2')" class="btn-primary" />
    </div>

    <x-drawer wire:model="showDrawer2" class="w-11/12 lg:w-1/3" right>
        <x-form wire:submit="saveShortlist" class="p-4">
            <div class="mt-4">
                <x-input icon="o-user" class="form-input" label="Name" wire:model="name" placeholder="Enter candidate name" />
            </div>
            <div class="mt-4">
                <x-input icon="o-phone" class="form-input" label="Phone" wire:model="phone" placeholder="Enter phone number" />
            </div>
            <div class="mt-4">
                <x-input icon="o-envelope" class="form-input" label="Email" wire:model="email" placeholder="Enter email address" />
            </div>
            <div class="mt-4">
                <x-input icon="o-briefcase" class="form-input" label="Job Role" wire:model="jobrole" placeholder="Enter job role" />
            </div>
            <div class="mt-4">
                @php
                    $statusOptions = [
                        ['id' => "Selected", 'name' => "Selected"],
                        ['id' => "Not Selected", 'name' => "Not Selected"]
                    ];
                @endphp
                <x-select class="form-select" placeholder="Choose the status" :options="$statusOptions" wire:model="status"></x-select>
            </div>
            <x-slot:actions class="mt-4 flex justify-end">
                <x-button label="Close" @click="$wire.showDrawer2 = false" class="mr-2" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    <!-- TABLE  -->
    <x-card class="shadow-lg rounded-lg  p-4">
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy">
            @scope('actions', $user)
            <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm {{ $user['status'] === 'Active' ? 'text-green-500' : 'text-red-500' }}" />
            @endscope
            @scope('cell_status', $department)
                @if($department['status'] == "Selected")
                    <x-badge value="Selected" class="badge-success" />
                @else
                    <x-badge value="Not Selected" class="badge-error" />
                @endif
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" class="mt-4 mb-4" />

        <x-slot:actions class="mt-4 flex justify-end">
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner class="mr-2" />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>

