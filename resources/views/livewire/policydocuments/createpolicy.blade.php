<?php

use App\Models\PolicyDocument;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator; 
use Illuminate\Support\Collection;
use Livewire\WithFileUploads;

new class extends Component {
    use Toast;
    use WithPagination;
    use WithFileUploads;
    public string $title = '';
    public string $description = '';
    public $file;
    public $selectedUser3;
    public function save()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'file' => 'required|mimes:pdf',
        ]);

        $path = $this->file->store('policy_documents' , 'public');
    
        $policy = new PolicyDocument();
        $policy->title = $this->title;
        $policy->description = $this->description;
        $policy->category = $this->selectedUser3;
        $policy->document = $path;
        $policy->save();

        $this->success('Policy document created successfully');
        $this->reset();

    }

};
?>

<div>
    <!-- Policy title , policy description , policy category , policy document -->
    <x-header title="Create Policy Document"/>
    <x-card>
    <x-form wire:submit="save">
            <div>
                <x-input label="Title" placeholder="Enter title" wire:model="title"/>
            </div>
            <div>
                <x-textarea label="Description" rows="5" placeholder="Enter description" wire:model="description"/>
            </div>
            <div>
                    @php
                    $categories = [
                        [
                            'id' => 1,
                            'name' => 'Leave Policy'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Probation Policy'
                        ],
                        [
                            'id' => 3,
                            'name' => 'Training Policy'
                        ],
                        [
                            'id' => 4,
                            'disabled' => true
                        ]
                    ];
                    @endphp
                    <x-select label="Select Category" placeholder="Select category"
                        :options="$categories" wire:model="selectedUser3" />
            </div>
            <div>
            <x-file wire:model="file" label="Receipt" hint="Only PDF" accept="application/pdf" />
            </div>
            <x-slot:actions>
        <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
    </x-slot:actions>
</x-form>
</x-card>
</div>