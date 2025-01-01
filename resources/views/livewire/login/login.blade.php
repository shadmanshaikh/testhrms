<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex justify-center">
        <img src="{{ asset('ruknlogo2.png') }}" alt="Rukn Logo" class="">
    </div>
        <div class="flex justify-center items-center">
            <div>
            </div>
        <form wire:submit="loginCheck">
            <x-card title="Login" subtitle="add your credentials" class="shadow shadow-lg" >
                
                <x-slot:menu>
                    <x-theme-toggle class="btn-sm btn-circle" />
                </x-slot:menu>

                <x-input type="text" wire:model="email" placeholder="Enter your username" class="form-input mt-1 block w-full mb-2" />
                <x-input type="password" wire:model="password" placeholder="Enter your password" class="form-input mt-1 block w-full" />
                <x-slot:actions>
                    <x-button label="login" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
                </x-slot:actions>
            </x-card>
        </form>
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
