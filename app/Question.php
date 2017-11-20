<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['description'];
    function answers(){
        return $this->belongsToMany(Answer::class,'question_answer','question_id','answer_id');
    }
}
