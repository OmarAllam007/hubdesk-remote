<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = ['value', 'level_id', 'level', 'level_id', 'type', 'available_until','label'];

    const  TYPES = ['Day', 'Month', 'Year'];

    const DAY = 1;
    const MONTH = 2;
    const Year = 3;

    protected $casts = [
        'value' => 'array'
    ];


}
