<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterSubgroup extends Model
{
    use SoftDeletes;

    protected $fillable = ['group_id', 'name', 'order'];

    function group()
    {
        return $this->belongsTo(LetterGroup::class);
    }
}
