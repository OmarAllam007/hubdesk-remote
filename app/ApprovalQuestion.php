<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalQuestion extends Model
{
    protected $fillable = ['description', 'approval_id', 'answer'];
}
