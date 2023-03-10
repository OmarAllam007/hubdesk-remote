<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['description','question_id','degree','is_default'];

    function question(){
        return $this->belongsTo(Question::class);
    }
}
