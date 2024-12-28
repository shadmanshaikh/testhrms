
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 overflow-hidden">
        <x-stat title="New Hires" value="44" icon="o-user" tooltip="Welcome!" class="text-blue-500" />
        <x-stat title="Employee Engagement" description="This quarter" value="85%" icon="o-face-smile" tooltip-bottom="Great job!" class="text-green-500" />
        <x-stat title="Turnover Rate" description="This quarter" value="12%" icon="o-archive-box" tooltip-left="Room for improvement" class="text-red-500" />
        <x-stat title="Training Sessions" description="This quarter" value="20" icon="o-book-open" tooltip-right="Knowledge is power!" class="text-purple-500" />
    </div>


</div>
