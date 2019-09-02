<?php

namespace App;

use App\Reports\CustomReport;
use Illuminate\Database\Eloquent\Model;

class ReportFolder extends Model
{
    protected $fillable = ['name'];


    function reports(){
        return $this->hasMany(Report::class);
    }

    function custom_reports(){
        return $this->hasMany(CustomReport::class);
    }
}
