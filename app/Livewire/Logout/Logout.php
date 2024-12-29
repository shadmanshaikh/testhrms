<?php

namespace App\Livewire\Logout;
use Illuminate\Support\Facades\Auth;


use Livewire\Component;

class Logout extends Component
{
    public function render()
    {
        return view('livewire.logout.logout');
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('status', 'You have been logged out.');
    }
}
