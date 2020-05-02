<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceLimit extends Model
{
    protected $fillable = [
        'business_unit_id',
        'level_id',
        'level',
        'value',
        'label',
        'number_of_tickets'
    ];

    protected $casts = ['value' => 'array'];

}
