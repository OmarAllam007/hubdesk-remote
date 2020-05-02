<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DashboardUser extends Model
{
    protected $fillable = ['user_id','business_unit_id'];
}
