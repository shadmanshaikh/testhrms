<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyDocument extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'category',
        'document'
    ];



}
