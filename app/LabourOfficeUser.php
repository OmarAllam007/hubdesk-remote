<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourOfficeUser extends Model
{
    use HasFactory;

    protected $table = 'users_labour_office';

    protected $fillable = ['employee_id', 'ar_name', 'nationality', 'building_no', 'building_name', 'border_no', 'iqama_number',
        'job', 'iqama_expire_date', 'ksa_entrance_date',
        'employee_type', 'employee_status', 'company'];
}
