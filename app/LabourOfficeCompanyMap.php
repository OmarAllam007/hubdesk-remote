<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourOfficeCompanyMap extends Model
{
    use HasFactory;

    protected $fillable = ['business_unit_id', 'ar_name', 'en_name'];
}
