<?php

namespace App\Livewire\Policydocuments;

use Livewire\Component;
use Livewire\Attributes\Layout;


class Showpolicy extends Component
{
    #[Layout('components.layouts.company-policy')]
    public $name;
    public $data;
    public function mount(){
        $this->data = \App\Models\PolicyDocument::where('title', $this->name)->first(); 
        // dd($this->data);
    }
    public function render()
    {
        return view('livewire.policydocuments.showpolicy');
    }
}
