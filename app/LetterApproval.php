<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LetterApproval extends Model
{
    protected $fillable = ['business_unit_id', 'role_id', 'user_id' , 'order'];

    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    function role()
    {
        return $this->belongsTo(Role::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
