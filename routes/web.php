<?php

use App\Http\Controllers\ollamaController;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Auth;
use PhpParser\Builder\Class_;

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
Volt::route('/departments' , 'departments.department')->middleware('auth');

//employees
Volt::route('/employee/employee-list' , 'employees.employee')->middleware('auth');
Volt::route('/employee/add-employee' , 'employees.addemployee')->middleware('auth');
Volt::route('/employee/{name}', 'employees.indemp')->middleware('auth');

// Volt::route('/dashboard')

//leads
Volt::route('/leads', 'leads.leads')->middleware('auth');

//jobs

Volt::route('/jobs', 'recruitment.jobs')->middleware('auth');
Volt::route('/shortlist', 'recruitment.shortlist')->middleware('auth');
Volt::route('/interviews', 'recruitment.interview')->middleware('auth');

//payroll

Volt::route('/payroll/index' , 'payroll.index')->middleware('auth');
Volt::route('/payroll/generate' , 'payroll.generate')->middleware('auth');
Volt::route('/payroll/generate/generate-slip' , 'payroll.generate-slip')->middleware('auth');

// policy documents 
Volt::route('/policy-documents' , 'policydocuments.policydocs')->middleware('auth');

// create policy document
Volt::route('/policy-documents/create' , 'policydocuments.createpolicy')->middleware('auth');

// page to show policies 
Volt::route('/policy/{name}' , 'policydocuments.showpolicy');

// leave requests
Volt::route('/leave-requests' , 'employees.leaverequest')->middleware('auth');

//ai

//ai -> reports

Volt::route('/ai/reports', 'ai.reports.reports')->middleware('auth');


//settings

Volt::route('/config', 'settings.appconfigs')->middleware('auth');
Volt::route('/create-user' , 'createuser.create')->middleware('auth');


Route::get('/ollama' , [ollamaController::class , 'index'])->middleware('auth');
Volt::route('/assistant' , 'ai.assistant')->middleware('auth');

Route::get('/offline', function () {
    return view('modules/laravelpwa/offline');
});
