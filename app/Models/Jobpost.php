<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobpost extends Model
{
    //
    protected $fillable = ['department', 'job_role', 'jd', 'salary', 'deadline', 'date_posted'];
}
