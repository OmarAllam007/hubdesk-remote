<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = ['level_id', 'level', 'to', 'cc'];

    protected $casts = [
        'cc' => 'array',
        'to' => 'array',
    ];
}
