<?php

use Livewire\Volt\Volt;

Volt::route('/', 'dashboard.dashboard');

//departments
Volt::route('/departments' , 'departments.department');

//employees
Volt::route('/employee' , 'employees.employee');
Volt::route('/add-employee' , 'employees.addemployee');
// Volt::route('/dashboard')

//leads
Volt::route('/leads', 'leads.leads');

//jobs

Volt::route('/jobs', 'recruitment.jobs');
Volt::route('/shortlist', 'recruitment.shortlist');
Volt::route('/interviews', 'recruitment.interview');

//payroll

Volt::route('/payroll/index' , 'payroll.index');


