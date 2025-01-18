<?php

use App\Models\Lead;
use App\Models\PolicyDocument;
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

    public array $sortBy = ['column' => 'title', 'direction' => 'asc'];
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
            ['key' => 'title', 'label' => 'Title', 'class' => 'w-96', 'sortable' => true],
            ['key' => 'category', 'label' => 'Category', 'class' => 'w-64', 'sortable' => true],
        ];

    }

    public function data(): LengthAwarePaginator {
        return PolicyDocument::query()
        ->when($this->sortBy['column'], function ($query) {
            $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
        })
        ->when($this->search, function ($query) {
            $query->where('title', 'like', '%' . $this->search . '%');
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
    <x-header title="Create Policy Document">
            <x-slot:middle class="!justify-end">
                <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
            </x-slot:middle>
            <x-slot:actions>
                <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
            </x-slot:actions>
    </x-header>

    <div class="text-end mb-4 flex justify-end">
        <x-button label="Add Policy" icon="o-plus" class="btn-primary btn-sm" link="/policy-documents/create" />
    </div>
 
    <x-card class="shadow-lg rounded-lg  p-4">
        <x-table :headers="$headers" :rows="$data" class="table-auto w-full">
        @scope('actions', $user)
            <div class="flex justify-end space-x-2">
                <x-button icon="o-eye" link="{{$user->document}}" spinner class="btn-sm" />
                <x-button icon="o-globe-alt" link="/policy/{{$user->title}}" spinner class="btn-sm btn-primary" />
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
