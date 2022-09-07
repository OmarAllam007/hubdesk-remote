<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCardBusinessUnitView extends Model
{
    use HasFactory;

    protected $fillable = ['business_unit_id','view_path'];
}
