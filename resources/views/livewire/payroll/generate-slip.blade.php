<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Employeeinfo;
use App\Models\Setsalary;

new class extends Component {
    // Your code here
    public $data , $empid , $basesalary , $selectedUser;

    public function mount(){
      
        
    }
    public function dataofEMP(): Collection
    {
        $this->data = Employeeinfo::all();
        return $this->data;
    }
    public function getdata(){
        $user = Employeeinfo::where('id' , $this->selectedUser)->value("name");
        $this->empid = Employeeinfo::where("name" , $user)->value("employee_id");
        $this->basesalary = Setsalary::where("name" ,$user)->value("base");
        // dd($this->empid);
    }
    public function with(): array {
        return [
            'empdata' => $this->dataofEMP(),
        ];
    }

};
?>

<div>
    <x-header title="Generate Salary Slip"></x-header>

    <x-card>
        <div class="lg:grid grid-cols-3 grid-rows-3 gap-3 md:grid grid-cols-3 grid-rows-1 gap-3">
            <div>
                <x-select placeholder="Select Employee" :options="$empdata" wire:model="selectedUser" />
            <x-button wire:click="getdata" class="btn-sm" label="get data"></x-button>
            </div>
            <div>
               <x-input type="text" placeholder="Employee ID" wire:model.live="empid" disabled  />
              
            </div>
            <div>
                 <x-input  placeholder="Base Salary" wire:model="basesalary" disabled/>
            </div>
        </div>
    </x-card>
</div>