<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Auth;

Volt::route('/', 'dashboard.dashboard')->name('home')->middleware('auth');
Volt::route('/login' , 'login.login')->name('login');  
Route::get('/logout' , function(){
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login')->with('status', 'You have been logged out.')->withHeaders([
        'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT'
    ]);
});
//departments
Volt::route('/departments' , 'departments.department')->middleware('auth');;

//employees
Volt::route('/employee' , 'employees.employee')->middleware('auth');;
Volt::route('/add-employee' , 'employees.addemployee')->middleware('auth');;
// Volt::route('/dashboard')

//leads
Volt::route('/leads', 'leads.leads')->middleware('auth');;

//jobs

Volt::route('/jobs', 'recruitment.jobs')->middleware('auth');;
Volt::route('/shortlist', 'recruitment.shortlist')->middleware('auth');;
Volt::route('/interviews', 'recruitment.interview')->middleware('auth');;

//payroll

Volt::route('/payroll/index' , 'payroll.index')->middleware('auth');;
Volt::route('/payroll/generate' , 'payroll.generate')->middleware('auth');;
Volt::route('/payroll/generate/generate-slip' , 'payroll.generate-slip')->middleware('auth');;


