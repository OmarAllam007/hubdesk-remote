<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserComplaint extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'description', 'type'];

    const TYPES = [1 => 'Approval Rejection', 2 => 'Quality of Service', 3 => 'Quality of Technician', 4 => 'Time Taken', 5 => 'Other'];

    function getTypeStrAttribute()
    {
        return self::TYPES[$this->type] ?? 'Not Assigned';
    }
}
