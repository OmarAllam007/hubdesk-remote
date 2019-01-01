<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessUnitRole extends Model
{
    protected $table = 'business_unit_roles';
    protected $fillable = ['business_unit_id', 'role_id', 'user_id'];

    function role()
    {
        return $this->belongsTo(Role::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
