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


};
?>

<div>
    <!-- Policy title , policy description , policy category , policy document -->
    <x-header title="Create Policy Document"/>
    <x-card  >
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
</x-card>
</div>