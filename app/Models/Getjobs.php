<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Getjobs extends Model
{
    //
    protected $table = 'jober';
    protected $fillable = [
        'title',
        'department',
        'type',
        'location', 
        'description',
        'requirements',
        'salary_range',
        'status',
        'deadline'
    ];

    protected $casts = [
        'deadline' => 'date'
    ];
}
