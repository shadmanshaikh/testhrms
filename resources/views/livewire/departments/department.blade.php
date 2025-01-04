<?php

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator; 
new class extends Component {
    use Toast;
    use WithPagination;
    public string $search = '';
    public string $name , $code , $description , $managerID , $status ;
    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public bool $showDrawer2 = false;

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
            ['key' => 'code', 'label' => 'Code', 'class' => 'w-20'],
            ['key' => 'description', 'label' => 'Description', 'sortable' => false],
            ['key' => 'manager_id', 'label' => 'Manager'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }

    /**
     * For demo purpose, this is a static collection.
     *
     * On real projects you do it with Eloquent collections.
     * Please, refer to maryUI docs to see the eloquent examples.
     */
    public function users(): LengthAwarePaginator 
    {
        return Department::query()
        ->when($this->sortBy['column'], function ($query) {
            $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
        })
        ->when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->paginate(5);
    }

    public function save(){

        $department = new Department();
        $department->name = $this->name;
        $department->code = $this->code;
        $department->description = $this->description;
        $department->manager_id = $this->managerID;
        $department->status = $this->status;
        $department->save();

        $this->showDrawer2 = false;
        $this->reset(['name', 'code', 'description', 'managerID', 'status']);
        $this->success('Department added successfully');
        // dd($this->name , $this->code , $this->managerID , $this->description , $this->status);
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
    <x-header title="List of Departments" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>
    </x-header>

    <!--  ADD DRAWER  -->

    <x-form wire:submit="save">
    <x-drawer Title="Add a New Department" wire:model="showDrawer2" separator  class="lg:w-1/3" right>
    <div>
        <div class="mb-3">
            <x-input label="Name" placeholder="eg. Marketing" wire:model="name"/>
        </div>
        <div class="mt-3">
            <x-input label="Code" placeholder="eg. MRK343" wire:model="code"/>
        </div>
        <div class="mt-3">
        <x-textarea
                label="Description"
                wire:model="description"
                placeholder="Describe about the department ..."
                rows="3"
                inline />
        </div>
        <div class="mt-3">
              <x-input label="Manager ID" placeholder="eg. SDE3" wire:model="managerID"/>
        </div>
        <div class="mt-3">
                @php
                    $selector = [
                        [
                            'id' => 1,
                            'name' => 'Active'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Inactive',
                          
                        ]
                    ];
                @endphp
                
                <x-select label="Status" placeholder="Select" :options="$selector" wire:model="status" />
        </div>
    </div>
    <x-slot:actions>
        <x-button label="Cancel" @click="$wire.showDrawer2 = false"/>
        <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
    </x-slot:actions>
</x-form>
</x-drawer>

<div class="flex justify-end mt-3 mb-3">
    <x-button responsive icon="o-plus" label="Department" class="btn-primary btn-sm" wire:click="$toggle('showDrawer2')" />
</div>
    <!-- TABLE  -->
    <x-card class="shadow-lg rounded-lg  p-4">
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" with-pagination class="table-auto w-full">
            @scope('actions', $user)
            <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-500 hover:text-red-700" />
            @endscope

            @scope('cell_status', $department)
                @if($department['status'] == "Yes")
                    <x-badge value="Active" class="badge-success" />
                @else
                    <x-badge value="Inactive" class="badge-error" />
                @endif
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
