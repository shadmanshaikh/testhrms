<?php

use App\Models\Lead;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator; 
use Illuminate\Support\Collection;

new class extends Component {
    use Toast;
    use WithPagination;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $source = '';
    public $notes = '';
    public $sources;
    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public string $search = '';
    public function clear(): void
    {
        $this->reset();
    }
    public function saveLead()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'source' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        \App\Models\Lead::create($validated);
        // dd($validated);
        $this->success('Lead created successfully');
        $this->reset(['name', 'email', 'phone', 'source', 'notes']);
        $this->dispatch('close-drawer', 'add-lead-drawer');
    }

    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-96', 'sortable' => true],
            ['key' => 'email', 'label' => 'Email', 'class' => 'w-48', 'sortable' => true],
            ['key' => 'phone', 'label' => 'Phone', 'class' => 'w-64', 'sortable' => true],
            ['key' => 'source', 'label' => 'Source', 'class' => 'w-32', 'sortable' => true],
            ['key' => 'notes', 'label' => 'Notes', 'class' => 'w-full', 'sortable' => false],
        ];

    }

    public function data(): LengthAwarePaginator {
        return Lead::query()
        ->when($this->sortBy['column'], function ($query) {
            $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
        })
        ->when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->paginate(5);
    }

    public function mount()
    {
        $this->sources = [
            [
                'id' => 'website',
                'name' => 'Website'
            ],
            [
                'id' => 'referral',
                'name' => 'Referral'
            ],
            [
                'id' => 'social_media',
                'name' => 'Social Media'
            ],
            [
                'id' => 'direct',
                'name' => 'Direct'
            ]
        ];
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
    <x-header title="Leads">
            <x-slot:middle class="!justify-end">
                <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
            </x-slot:middle>
            <x-slot:actions>
                <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
            </x-slot:actions>
    </x-header>

    <div class="text-end mb-4 flex justify-end">
        <label for="add-lead-drawer" class="btn btn-primary btn-sm">
            <x-icon name="o-plus" class="lg:w-5 h-5 me-2" /> Add Lead
        </label>
    </div>
    <x-form wire:submit="saveLead">
        <x-drawer id="add-lead-drawer" title="Add New Lead" class="lg:w-1/3" seperator right>
            <div class="space-y-4">
                <x-input label="Full Name" wire:model="name" required />
                
                <x-input label="Email" type="email" wire:model="email" required />
                
                <x-input label="Phone" type="tel" wire:model="phone" required />
                
                <x-select 
                        label="Lead Source"
                        wire:model="source"
                        :options="$sources"
                        required
                        placeholder="Select Source"
                    />
                
                <x-textarea label="Notes" wire:model="notes" />
                <x-slot:actions>
                    <x-button label="Cancel" class="btn-ghost" @click="$dispatch('close-drawer', 'add-lead-drawer')" />
                    <x-button label="Save Lead" class="btn-primary" type="submit" />
                </x-slot:actions>
            </div>
        </x-drawer>
    </x-form>
    <x-card class="shadow-lg rounded-lg  p-4">
        <x-table :headers="$headers" :rows="$data" class="table-auto w-full">
            @scope('cell_name', $lead)
                <div class="w-28">
                    <x-icon name="o-user" class=""/>  <x-badge :value="$lead->name"  />
                </div>
            @endscope
            @scope('cell_phone' , $personphone)
                <div class="w-36">
                <x-icon name="o-phone" class=""/> 
                <x-badge :value="$personphone->phone"  class="badge-primary" />
                </div>
            @endscope
            @scope('cell_email' , $inemail)
                <div class="w-52">
                    <x-icon name="o-envelope"/>
                    <x-badge :value="$inemail->email"  class="" />
                </div>
            @endscope
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
