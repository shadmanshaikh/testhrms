<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public function with(): array
    {
        return [
            
        ];
    }
};
?>

<div>
    <x-header title="App Configuration" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
    </x-header>

    <x-card class="mt-4">
        <!-- Configuration conten  t will go here -->
        <div class="flex items-center justify-between p-6 border-b border-base-200 hover:bg-base-100/50 transition-colors duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-2 rounded-lg bg-primary/10">
                    <x-icon name="o-moon" class="w-6 h-6 text-primary" />
                </div>
                <div>
                    <h3 class="font-semibold text-base">Dark Mode</h3>
                    <p class="text-sm text-gray-500">Toggle between light and dark themes</p>
                </div>
            </div>
            <x-theme-toggle class="btn btn-circle btn-primary btn-outline hover:btn-primary" />
        </div>

        <!-- Language Settings -->
        <div class="flex items-center justify-between p-6 border-b border-base-200 hover:bg-base-100/50 transition-colors duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-2 rounded-lg bg-info/10">
                    <x-icon name="o-language" class="w-6 h-6 text-info" />
                </div>
                <div>
                    <h3 class="font-semibold text-base">Language</h3>
                    <p class="text-sm text-gray-500">Select your preferred language</p>
                </div>
            </div>
            <x-select wire:model.live="language" class="w-40">
                <option value="en">English</option>
                <option value="es">Spanish</option>
                <option value="fr">French</option>
                <option value="ar">Arabic</option>
            </x-select>
        </div>

        <!-- Notification Settings -->
        <div class="flex items-center justify-between p-6 border-b border-base-200 hover:bg-base-100/50 transition-colors duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-2 rounded-lg bg-warning/10">
                    <x-icon name="o-bell" class="w-6 h-6 text-warning" />
                </div>
                <div>
                    <h3 class="font-semibold text-base">Notifications</h3>
                    <p class="text-sm text-gray-500">Manage your notification preferences</p>
                </div>
            </div>
            <x-toggle wire:model.live="notifications" />
        </div>

        <!-- Time Zone Settings -->
        <div class="flex items-center justify-between p-6 border-b border-base-200 hover:bg-base-100/50 transition-colors duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-2 rounded-lg bg-success/10">
                    <x-icon name="o-clock" class="w-6 h-6 text-success" />
                </div>
                <div>
                    <h3 class="font-semibold text-base">Time Zone</h3>
                    <p class="text-sm text-gray-500">Set your local time zone</p>
                </div>
            </div>
            <x-select wire:model.live="timezone" class="w-60">
                <option value="UTC">UTC (Coordinated Universal Time)</option>
                <option value="GMT">GMT (Greenwich Mean Time)</option>
                <option value="EST">EST (Eastern Standard Time)</option>
                <option value="PST">PST (Pacific Standard Time)</option>
            </x-select>
        </div>

        <!-- Data Privacy Settings -->
        <div class="flex items-center justify-between p-6 hover:bg-base-100/50 transition-colors duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-2 rounded-lg bg-error/10">
                    <x-icon name="o-shield-check" class="w-6 h-6 text-error" />
                </div>
                <div>
                    <h3 class="font-semibold text-base">Data Privacy</h3>
                    <p class="text-sm text-gray-500">Manage your data sharing preferences</p>
                </div>
            </div>
            <x-toggle wire:model.live="dataSharing" />
        </div>
    </x-card>
</div>
