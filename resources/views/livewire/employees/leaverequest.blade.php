<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public string $search = '';

    public bool $drawer = false;

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

    public function with(): array
    {
        return [
            'users' => $this->users(),
            'headers' => $this->headers()
        ];
    }
}; ?>

<div class="min-h-screen  p-6">
    <!-- HEADER -->
    <x-header title="Leave Request" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>
    </x-header>

    <!-- Leave Requests List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <!-- Example Leave Request Card -->
        <x-card class="shadow-lg rounded-lg p-4">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <img class="h-12 w-12 rounded-full" src="{{ asset('storage/profile_photo_placeholder.png') }}" alt="User Name">
                </div>
                <div class="ml-4">
                    <div class="text-lg font-medium text-gray-900">John Doe</div>
                    <div class="text-sm text-gray-500">Software Engineer</div>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                <p><strong>Leave Type:</strong> Annual Leave</p>
                <p><strong>Start Date:</strong> 01 Jan, 2023</p>
                <p><strong>End Date:</strong> 10 Jan, 2023</p>
                <p><strong>Reason:</strong> Family Vacation</p>
            </div>
            <div class="mt-4 flex justify-end space-x-2">
                <x-button label="Approve" class="btn-success" />
                <x-button label="Reject" class="btn-danger" />
            </div>
        </x-card>

        <!-- Repeat the above card for more leave requests -->
        <x-card class="shadow-lg rounded-lg p-4">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <img class="h-12 w-12 rounded-full" src="{{ asset('storage/profile_photo_placeholder.png') }}" alt="User Name">
                </div>
                <div class="ml-4">
                    <div class="text-lg font-medium text-gray-900">Jane Smith</div>
                    <div class="text-sm text-gray-500">Project Manager</div>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                <p><strong>Leave Type:</strong> Sick Leave</p>
                <p><strong>Start Date:</strong> 15 Feb, 2023</p>
                <p><strong>End Date:</strong> 20 Feb, 2023</p>
                <p><strong>Reason:</strong> Medical Treatment</p>
            </div>
            <div class="mt-4 flex justify-end space-x-2">
                <x-button label="Approve" class="btn-success" />
                <x-button label="Reject" class="btn-danger" />
            </div>
        </x-card>
    </div>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
