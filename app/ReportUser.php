<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportUser extends Model
{
    protected $table = 'report_users';

    protected $fillable = ['report_id', 'user_id'];
}
