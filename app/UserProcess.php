<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProcess extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'last_payslip_generation'];

    function user()
    {
        return $this->belongsTo(User::class, 'employee_id','employee_id');
    }
}
