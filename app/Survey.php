<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['name'];

    function questions(){
        return $this->belongsToMany(Question::class,'survey_question','survey_id','question_id');
    }
}
