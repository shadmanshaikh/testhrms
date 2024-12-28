<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    //
    protected $fillable = [
        'intervieweeName',
        'interviewDate',
        'interviewTime',
        'interviewDetails'
    ];
}
