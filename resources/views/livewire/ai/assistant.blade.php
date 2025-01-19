<div class="container mx-auto p-6">
    <x-card class=" shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">AI Assistant</h2>
        <x-textarea class="mt-3 w-full p-2 border rounded" type="text" wire:model="prompt" placeholder="Enter your prompt here" inline />
        <x-button class="mt-3 btn-primary" wire:click="submit">Submit</x-button>
    </x-card>

    <x-card class=" shadow-md rounded-lg p-6 mt-6">
        <h3 class="text-xl font-semibold mb-4">Response</h3>
        <x-textarea class=" p-4 rounded w-full" wire:model="response" readonly rows="5"></x-textarea>
    </x-card>
</div>
