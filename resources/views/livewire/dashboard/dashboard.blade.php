
<?php

use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [
            'title' => 'Dashboard',
            'icon' => 'o-chart-pie',
            'link' => '/',
        ];
    }
}; ?>

<div>
    <x-header :title="$title" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" class="lg:w-auto w-full" />
        </x-slot:middle>
    </x-header>
    <div class="flex gap-3">
        <x-card title="No. of Male Employees">
                
        </x-card>
    

    </div>
</div>
