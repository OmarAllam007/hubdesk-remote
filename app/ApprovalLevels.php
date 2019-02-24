<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalLevels extends Model
{
    protected $table = 'approval_levels';

    protected $fillable = ['type','level_id','role_id'];

    static $types = [1 => 'Category', 2 => 'Subcategory', 3 => 'Item'];



}
