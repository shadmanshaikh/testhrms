<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setsalary extends Model
{
    //
    protected $fillable = [
        'emp_id' , 'name' , 'base' , 'joiningdate'
    ];
}
