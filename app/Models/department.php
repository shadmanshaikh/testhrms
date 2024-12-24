<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id',
        'status',
    ];

    // Define constants for the enum values
    const STATUS_YES = 'yes';
    const STATUS_NO = 'no';

    // Accessor for status attribute
    public function getStatusAttribute($value)
    {
        return $value === self::STATUS_YES ? 'Yes' : 'No';
    }

    // Mutator for status attribute
    public function setStatusAttribute($value)
    {   
        $this->attributes['status'] = $value ? self::STATUS_YES : self::STATUS_NO;
    }
}                    
