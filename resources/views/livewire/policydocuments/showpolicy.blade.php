<div class="">
    <x-header title="{{$name}}" class="p-3 bg-primary text-white" />
    <x-card>
        {{$data->description}}
    
        <div class="mt-4">
            <a href="{{asset('storage/'.$data->document)}}" class="btn btn-primary">Download</a>
        </div>
    </x-card>
    <x-theme-toggle />
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
</div>
